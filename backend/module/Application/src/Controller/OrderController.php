<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Middleware\JwtMiddleware;
use Application\Shared\Db;

class OrderController extends AbstractActionController
{
    public function listAction()
{
    try {
        $user = JwtMiddleware::validateToken();
        $adapter = Db::adapter();

        $sql = "
            SELECT 
                p.id AS pedido_id,
                p.data_pedido,
                p.total,
                p.status,
                c.nome AS cliente,
                i.produto_id,
                i.quantidade,
                i.preco_unitario,
                n.chave,
                n.numero,
                n.serie,
                n.url_xml,
                n.url_danfe
            FROM pedidos p
            JOIN clientes c ON p.cliente_id = c.id
            LEFT JOIN pedido_itens i ON p.id = i.pedido_id
            LEFT JOIN nfe n ON p.id = n.pedido_id
            ORDER BY p.id DESC
        ";

        $stmt = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
        $rows = iterator_to_array($stmt);

        $pedidos = [];
        foreach ($rows as $row) {
            $id = $row['pedido_id'];

            if (!isset($pedidos[$id])) {
                $pedidos[$id] = [
                    'id' => $id,
                    'cliente' => $row['cliente'],
                    'total' => $row['total'],
                    'status' => $row['status'],
                    'data_pedido' => $row['data_pedido'],
                    'itens' => [],
                    'nota_fiscal' => $row['chave'] ? [
                        'chave' => $row['chave'],
                        'numero' => $row['numero'],
                        'serie' => $row['serie'],
                        'url_xml' => $row['url_xml'],
                        'url_danfe' => $row['url_danfe']
                    ] : null
                ];
            }

            if ($row['produto_id']) {
                $pedidos[$id]['itens'][] = [
                    'produto_id' => $row['produto_id'],
                    'quantidade' => $row['quantidade'],
                    'preco_unitario' => $row['preco_unitario']
                ];
            }
        }

        return new JsonModel([
            'ok' => true,
            'user' => $user,
            'pedidos' => array_values($pedidos),
            'timestamp' => (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s')
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
            return new JsonModel(['ok'=>false,'error'=>'Campos obrigatórios faltando']);
        }

        $adapter = Db::adapter();

        $total = 0;
        foreach ($data['itens'] as $item) {
            $total += $item['quantidade'] * $item['preco_unitario'];
        }

        $sqlPedido = "INSERT INTO pedidos (cliente_id, total, status) 
                      VALUES (:cliente_id, :total, :status) RETURNING id";
        $stmtPedido = $adapter->createStatement($sqlPedido);
        $result = $stmtPedido->execute([
            'cliente_id' => $data['cliente_id'],
            'total'      => $total,
            'status'     => $data['status'] ?? 'pendente'
        ]);

        $pedidoId = $result->current()['id'];

        $sqlItem = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) 
                    VALUES (:pedido_id, :produto_id, :quantidade, :preco_unitario)";
        $stmtItem = $adapter->createStatement($sqlItem);

        foreach ($data['itens'] as $item) {
            $stmtItem->execute([
                'pedido_id'      => $pedidoId,
                'produto_id'     => $item['produto_id'],
                'quantidade'     => $item['quantidade'],
                'preco_unitario' => $item['preco_unitario']
            ]);
        }

        $client = new \Laminas\Http\Client('http://sdv.local/mock/nfe/emitir', [
            'adapter' => 'Laminas\Http\Client\Adapter\Curl',
            'timeout' => 30
        ]);

        $client->setMethod('POST');
        $client->setHeaders(['Content-Type' => 'application/json']);
        $client->setRawBody(json_encode([
            'pedido_id'  => $pedidoId,
            'cliente_id' => $data['cliente_id'],
            'total'      => $total,
            'itens'      => $data['itens']
        ]));

        $response = $client->send();

        $notaFiscal = [];
        if ($response->isSuccess()) {
            $notaFiscal = json_decode($response->getBody(), true);

            if (isset($notaFiscal['nfe'])) {
                $nfe = $notaFiscal['nfe'];

                $sqlNfe = "INSERT INTO nfe 
                    (pedido_id, chave, numero, serie, cliente_id, valor_total, qtd_itens, url_xml, url_danfe) 
                    VALUES 
                    (:pedido_id, :chave, :numero, :serie, :cliente_id, :valor_total, :qtd_itens, :url_xml, :url_danfe)";

                $stmtNfe = $adapter->createStatement($sqlNfe);
                $stmtNfe->execute([
                    'pedido_id'   => $pedidoId,
                    'chave'       => $nfe['chave'] ?? null,
                    'numero'      => $nfe['numero'] ?? null,
                    'serie'       => $nfe['serie'] ?? null,
                    'cliente_id'  => $nfe['cliente_id'] ?? $data['cliente_id'],
                    'valor_total' => $nfe['valor_total'] ?? $total,
                    'qtd_itens'   => $nfe['qtd_itens'] ?? count($data['itens']),
                    'url_xml'     => $nfe['url_xml'] ?? null,
                    'url_danfe'   => $nfe['url_danfe'] ?? null
                ]);
            }
        }

        return new JsonModel([
            'ok' => true,
            'message' => 'Pedido criado com sucesso',
            'pedido' => [
                'id'         => $pedidoId,
                'cliente_id' => $data['cliente_id'],
                'total'      => $total,
                'status'     => $data['status'] ?? 'pendente',
                'itens'      => $data['itens'],
                'criado_em'  => (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s'),
                'usuario'    => $user
            ],
            'nota_fiscal' => $notaFiscal
        ]);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
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
                    'cancelado_em' => (new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s'),
                    'usuario' => $user
                ]
            ]);

        } catch (\Exception $e) {
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }

    public function getAction()
{
    try {
        $user = JwtMiddleware::validateToken();
        $id = $this->params()->fromRoute('id');

        $adapter = Db::adapter();

        $sql = "SELECT p.*, c.nome as cliente_nome
                FROM pedidos p
                JOIN clientes c ON c.id = p.cliente_id
                WHERE p.id = :id";
        $stmt = $adapter->createStatement($sql);
        $pedido = $stmt->execute(['id' => $id])->current();

        if (!$pedido) {
            return new JsonModel(['ok'=>false,'error'=>'Pedido não encontrado']);
        }

        $sqlItens = "SELECT pi.*, pr.nome as produto_nome
                     FROM pedido_itens pi
                     JOIN produtos pr ON pr.id = pi.produto_id
                     WHERE pi.pedido_id = :id";
        $stmtItens = $adapter->createStatement($sqlItens);
        $itens = iterator_to_array($stmtItens->execute(['id'=>$id]));

        $pedido['itens'] = $itens;


        $sqlNfe = "SELECT * FROM nfe WHERE pedido_id = :id";
        $stmtNfe = $adapter->createStatement($sqlNfe);
        $nfe = $stmtNfe->execute(['id' => $id])->current();

        $notaFiscal = $nfe ? ['nfe' => $nfe] : null;

        return new JsonModel([
            'ok' => true,
            'pedido' => $pedido,
            'nota_fiscal' => $notaFiscal
        ]);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
    }
}

public function updateAction()
{
    try {
        $user = JwtMiddleware::validateToken();
        $id = $this->params()->fromRoute('id');
        $data = json_decode(file_get_contents('php://input'), true);

        $adapter = Db::adapter();

        // 1. Atualizar o pedido (cliente e status)
        $sql = "UPDATE pedidos 
                SET cliente_id = :cliente_id, status = :status
                WHERE id = :id";
        $stmt = $adapter->createStatement($sql);
        $stmt->execute([
            'id' => $id,
            'cliente_id' => $data['cliente_id'] ?? null,
            'status' => $data['status'] ?? 'aberto'
        ]);

        // 2. Atualizar os itens do pedido e calcular total
        $total = 0;
        $qtdItens = 0;

        if (!empty($data['itens']) && is_array($data['itens'])) {
            foreach ($data['itens'] as $item) {
                if (!isset($item['produto_id']) || !isset($item['quantidade'])) {
                    continue;
                }

                // Atualiza a quantidade
                $sqlItem = "UPDATE pedido_itens 
                            SET quantidade = :quantidade 
                            WHERE pedido_id = :pedido_id AND produto_id = :produto_id";
                $stmtItem = $adapter->createStatement($sqlItem);
                $stmtItem->execute([
                    'pedido_id' => $id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade']
                ]);

                // Buscar preço unitário
                $sqlPreco = "SELECT preco FROM produtos WHERE id = :id";
                $stmtPreco = $adapter->createStatement($sqlPreco);
                $precoRow = $stmtPreco->execute(['id' => $item['produto_id']])->current();

                $preco = isset($precoRow['preco']) ? (float)$precoRow['preco'] : 0;

                // Acumular total e quantidade
                $subtotal = $preco * $item['quantidade'];
                $total += $subtotal;
                $qtdItens += $item['quantidade'];
            }

            // 3. Atualizar o total do pedido
            $sqlTotal = "UPDATE pedidos SET total = :total WHERE id = :id";
            $stmtTotal = $adapter->createStatement($sqlTotal);
            $stmtTotal->execute([
                'total' => $total,
                'id' => $id
            ]);

            // 4. Atualizar a nota fiscal (se existir)
            $sqlCheckNfe = "SELECT id FROM nfe WHERE pedido_id = :id";
            $stmtCheck = $adapter->createStatement($sqlCheckNfe);
            $nfeRow = $stmtCheck->execute(['id' => $id])->current();

            if ($nfeRow) {
                $sqlUpdateNfe = "UPDATE nfe 
                                 SET valor_total = :valor_total, qtd_itens = :qtd_itens 
                                 WHERE pedido_id = :pedido_id";
                $stmtUpdateNfe = $adapter->createStatement($sqlUpdateNfe);
                $stmtUpdateNfe->execute([
                    'valor_total' => $total,
                    'qtd_itens'   => $qtdItens,
                    'pedido_id'   => $id
                ]);
            }
        }

        return new JsonModel(['ok' => true, 'message' => 'Pedido e nota fiscal atualizados com sucesso']);

    } catch (\Exception $e) {
        return new JsonModel(['ok' => false, 'error' => $e->getMessage()]);
    }
}

    public function deleteAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $id = $this->params()->fromRoute('id');

            $adapter = Db::adapter();
            $connection = $adapter->getDriver()->getConnection();
            $connection->beginTransaction();

            $sqlItens = "DELETE FROM pedido_itens WHERE pedido_id = :id";
            $adapter->createStatement($sqlItens)->execute(['id' => $id]);

            $sql = "DELETE FROM pedidos WHERE id = :id";
            $adapter->createStatement($sql)->execute(['id' => $id]);

            $connection->commit();

            return new JsonModel(['ok'=>true,'message'=>'Pedido deletado com sucesso']);

        } catch (\Exception $e) {
            if (isset($connection)) $connection->rollback();
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }
}
