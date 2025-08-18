<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Middleware\JwtMiddleware;
use Application\Shared\Db;

class ClientController extends AbstractActionController
{
    // Listar clientes
    public function listAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $adapter = Db::adapter();

            $sql = "SELECT * FROM clientes ORDER BY id DESC";
            $stmt = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
            $clients = iterator_to_array($stmt);

            return new JsonModel([
                'ok' => true,
                'user' => $user,
                'clients' => $clients
            ]);

        } catch (\Exception $e) {
            return new JsonModel([
                'ok' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Criar cliente
    public function createAction()
    {
        try {
            $user = JwtMiddleware::validateToken();
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['nome'], $data['email'], $data['cnpj'])) {
                return new JsonModel(['ok'=>false,'error'=>'Campos obrigatórios faltando']);
            }

            $adapter = Db::adapter();
            $sql = "INSERT INTO clientes (nome, email, cnpj, telefone, endereco)
                    VALUES (:nome, :email, :cnpj, :telefone, :endereco)";
            $stmt = $adapter->createStatement($sql);
            $stmt->execute([
                'nome' => $data['nome'],
                'email' => $data['email'],
                'cnpj' => $data['cnpj'],
                'telefone' => $data['telefone'] ?? '',
                'endereco' => $data['endereco'] ?? ''
            ]);

            return new JsonModel(['ok'=>true,'message'=>'Cliente criado com sucesso']);

        } catch (\Exception $e) {
            return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
        }
    }
}
