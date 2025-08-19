<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Middleware\JwtMiddleware;
use Application\Shared\Db;
use Application\Service\NfeService;


class OrderController extends AbstractActionController
{
    public function listAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $adapter = Db::adapter();

            $sqlPedidos = "SELECT p.id, p.data_pedido, p.total, p.status, c.nome as cliente
                           FROM pedidos p
                           JOIN clientes c ON p.cliente_id = c.id
                           ORDER BY p.id DESC";
            $stmtPedidos = $adapter->query($sqlPedidos, $adapter::QUERY_MODE_EXECUTE);
            $pedidosRaw = iterator_to_array($stmtPedidos);

            $pedidos = [];
            foreach ($pedidosRaw as $pedido) {
                $sqlItens = "SELECT produto_id, quantidade, preco_unitario
                             FROM pedido_itens WHERE pedido_id = :pedido_id";
                $stmtItens = $adapter->createStatement($sqlItens);
                $itensResult = $stmtItens->execute(['pedido_id' => $pedido['id']]);
                $itens = iterator_to_array($itensResult);

                $pedidos[] = [
                    'id' => $pedido['id'],
                    'cliente' => $pedido['cliente'],
                    'total' => $pedido['total'],
                    'status' => $pedido['status'],
                    'data_pedido' => $pedido['data_pedido'],
                    'itens' => $itens
                ];
            }

            return new JsonModel([
                'ok' => true,
                'user' => $user,
                'pedidos' => $pedidos,
                'timestamp' => date('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return new JsonModel([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

  public function createAction()
{
    try {
        $user = JwtMiddleware::validateToken();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['cliente_id'], $data['itens']) || !is_array($data['itens'])) {
            return new JsonModel(['ok' => false, 'error' => 'Campos obrigatórios faltando']);
        }

        $adapter = Db::adapter();

        $total = 0;
        foreach ($data['itens'] as $item) {
            $total += $item['quantidade'] * $item['preco_unitario'];
        }

        $sqlPedido = "INSERT INTO pedidos (cliente_id, total, status) VALUES (:cliente_id, :total, :status) RETURNING id";
        $stmtPedido = $adapter->createStatement($sqlPedido);
        $result = $stmtPedido->execute([
            'cliente_id' => $data['cliente_id'],
            'total' => $total,
            'status' => $data['status'] ?? 'pendente'
        ]);

        $pedidoId = $result->current()['id'];

        $sqlItem = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) 
                    VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario)";
        $stmtItem = $adapter->createStatement($sqlItem);

        foreach ($data['itens'] as $item) {
            $stmtItem->execute([
                'pedido_id' => $pedidoId,
                'produto_id' => $item['produto_id'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco_unitario']
            ]);
        }

        // Emissão NF-e com dados de teste
        $nfeService = new \Application\Service\NfeService();
        $nfeData = [
            "natureza_operacao" => "Remessa",
            "data_emissao" => date('Y-m-d\TH:i:s'),
            "data_entrada_saida" => date('Y-m-d\TH:i:s'),
            "tipo_documento" => "1",
            "finalidade_emissao" => "1",
            "cnpj_emitente" => "51916585000125", // CNPJ de teste
            "nome_emitente" => "Empresa Teste LTDA",
            "nome_fantasia_emitente" => "Empresa Teste",
            "logradouro_emitente" => "Rua Exemplo",
            "numero_emitente" => "100",
            "bairro_emitente" => "Centro",
            "municipio_emitente" => "Cidade Teste",
            "uf_emitente" => "SP",
            "cep_emitente" => "01000000",
            "inscricao_estadual_emitente" => "123456789",
            "nome_destinatario" => "Cliente Teste",
            "cpf_destinatario" => "12345678901",
            "items" => array_map(function($item, $index) {
                return [
                    "numero_item" => (string)($index+1),
                    "codigo_produto" => (string)$item['produto_id'],
                    "descricao" => "Produto teste",
                    "cfop" => "5102",
                    "unidade_comercial" => "un",
                    "quantidade_comercial" => (string)$item['quantidade'],
                    "valor_unitario_comercial" => (string)$item['preco_unitario'],
                    "valor_unitario_tributavel" => (string)$item['preco_unitario'],
                    "unidade_tributavel" => "un",
                    "quantidade_tributavel" => (string)$item['quantidade'],
                    "valor_bruto" => (string)($item['quantidade'] * $item['preco_unitario']),
                    "icms_situacao_tributaria" => "400",
                    "icms_origem" => "0",
                    "pis_situacao_tributaria" => "07",
                    "cofins_situacao_tributaria" => "07"
                ];
            }, $data['itens'], array_keys($data['itens']))
        ];

        $nfeResult = $nfeService->emitirNfe($nfeData);

        $message = $nfeResult['ok'] ? 'Pedido e NF-e criados com sucesso' :
            'Pedido criado com sucesso, mas houve falha ao emitir a NF-e.';

        return new JsonModel([
            'ok' => true,
            'message' => $message,
            'pedido' => [
                'id' => $pedidoId,
                'cliente_id' => $data['cliente_id'],
                'total' => $total,
                'status' => $data['status'] ?? 'pendente',
                'itens' => $data['itens'],
                'criado_em' => date('Y-m-d H:i:s'),
                'usuario' => $user
            ],
            'nfe' => $nfeResult
        ]);

    } catch (\Exception $e) {
        return new JsonModel(['ok' => false, 'error' => $e->getMessage()]);
    }
}


    public function cancelAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['pedido_id'])) {
                return new JsonModel(['ok'=>false,'error'=>'pedido_id obrigatório']);
            }

            $adapter = Db::adapter();
            $pedidoId = $data['pedido_id'];

            $sqlStatus = "SELECT status FROM pedidos WHERE id = :pedido_id";
            $stmtStatus = $adapter->createStatement($sqlStatus);
            $resultStatus = $stmtStatus->execute(['pedido_id' => $pedidoId])->current();

            if (!$resultStatus) {
                return new JsonModel(['ok'=>false,'error'=>'Pedido não encontrado']);
            }

            if ($resultStatus['status'] !== 'pendente') {
                return new JsonModel(['ok'=>false,'error'=>'Apenas pedidos pendentes podem ser cancelados']);
            }

            $sqlItens = "SELECT produto_id, quantidade FROM pedido_itens WHERE pedido_id = :pedido_id";
            $stmtItens = $adapter->createStatement($sqlItens);
            $resultItens = $stmtItens->execute(['pedido_id' => $pedidoId]);

            foreach ($resultItens as $item) {
                $sqlUpdate = "UPDATE produtos SET estoque = estoque + :qtd WHERE id = :produto_id";
                $stmtUpdate = $adapter->createStatement($sqlUpdate);
                $stmtUpdate->execute([
                    'qtd' => $item['quantidade'],
                    'produto_id' => $item['produto_id']
                ]);
            }

            $sqlCancel = "UPDATE pedidos SET status = 'cancelado' WHERE id = :pedido_id";
            $stmtCancel = $adapter->createStatement($sqlCancel);
            $stmtCancel->execute(['pedido_id' => $pedidoId]);

            return new JsonModel([
                'ok' => true,
                'message' => "Pedido {$pedidoId} cancelado e estoque devolvido",
                'pedido' => [
                    'id' => $pedidoId,
                    'status' => 'cancelado',
                    'cancelado_em' => date('Y-m-d H:i:s'),
                    'usuario' => $user
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }
}
