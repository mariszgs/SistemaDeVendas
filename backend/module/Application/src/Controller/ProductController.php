<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Middleware\JwtMiddleware;
use Application\Shared\Db; 

class ProductController extends AbstractActionController
{
 
    public function listAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $adapter = Db::adapter(); 

      
            $sql = "SELECT * FROM produtos ORDER BY id DESC";
            $stmt = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);

      
            $products = iterator_to_array($stmt);

            return new JsonModel([
                'ok' => true,
                'user' => $user,
                'products' => $products
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

            if (!isset($data['nome'], $data['preco'], $data['estoque'])) {
                return new JsonModel(['ok'=>false,'error'=>'Campos obrigatórios faltando']);
            }

            $adapter = Db::adapter();
            $sql = "INSERT INTO produtos (nome, descricao, preco, estoque) 
                    VALUES (:nome, :descricao, :preco, :estoque)";

            $stmt = $adapter->createStatement($sql);
            $stmt->execute([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'] ?? '',
                'preco' => $data['preco'],
                'estoque' => $data['estoque']
            ]);

            return new JsonModel(['ok'=>true,'message'=>'Produto criado com sucesso']);

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
            $sql = "SELECT * FROM produtos WHERE id = :id";
            $stmt = $adapter->createStatement($sql);
            $result = $stmt->execute(['id' => $id])->current();

            if (!$result) {
                return new JsonModel(['ok'=>false,'error'=>'Produto não encontrado']);
            }

            return new JsonModel(['ok'=>true,'product'=>$result]);

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
            $sql = "UPDATE produtos 
                    SET nome = :nome, preco = :preco, descricao = :descricao, estoque = :estoque
                    WHERE id = :id";
            $stmt = $adapter->createStatement($sql);
            $stmt->execute([
                'id' => $id,
                'nome' => $data['nome'] ?? '',
                'preco' => $data['preco'] ?? 0,
                'descricao' => $data['descricao'] ?? '',
                'estoque' => $data['estoque'] ?? 0
            ]);

            return new JsonModel(['ok'=>true,'message'=>'Produto atualizado com sucesso']);

        } catch (\Exception $e) {
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }

    public function deleteAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $id = $this->params()->fromRoute('id');

            $adapter = Db::adapter();
            $sql = "DELETE FROM produtos WHERE id = :id";
            $stmt = $adapter->createStatement($sql);
            $stmt->execute(['id' => $id]);

            return new JsonModel(['ok'=>true,'message'=>'Produto deletado com sucesso']);

        } catch (\Exception $e) {
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }
}
