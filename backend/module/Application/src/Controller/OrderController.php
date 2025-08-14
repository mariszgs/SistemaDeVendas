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

            $sql = "SELECT p.id, p.data_pedido, p.total, p.status, c.nome as cliente
                    FROM pedidos p
                    JOIN clientes c ON p.cliente_id = c.id
                    ORDER BY p.id DESC";

            $stmt = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
            $orders = iterator_to_array($stmt);

            return new JsonModel([
                'ok' => true,
                'user' => $user,
                'orders' => $orders
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

        return new JsonModel(['ok'=>true,'message'=>'Pedido criado com sucesso', 'pedido_id'=>$pedidoId]);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
    }
  } 
}
