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
            $user = JwtMiddleware::validateToken(); // valida JWT
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
}
