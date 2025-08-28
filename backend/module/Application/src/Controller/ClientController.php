<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Application\Middleware\JwtMiddleware;
use Application\Shared\Db;

class ClientController extends AbstractActionController
{
    public function listAction()
{
    try {
        $user = JwtMiddleware::validateToken();
        $adapter = Db::adapter();

        $sql = "SELECT * FROM clientes ORDER BY id DESC";
        $stmt = $adapter->query($sql, $adapter::QUERY_MODE_EXECUTE);
        $clients = iterator_to_array($stmt);

        if ($this->params()->fromQuery('format') === 'json') {
            return new JsonModel([
                'ok' => true,
                'user' => $user,
                'clients' => $clients
            ]);
        }

        return new ViewModel([
            'clients' => $clients,
            'user'    => $user
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

    public function getAction()
    {
        try {
        $user = JwtMiddleware::validateToken();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id <= 0) {
            return new JsonModel(['ok'=>false,'error'=>'ID inválido']);
        }

        $adapter = Db::adapter();
        $sql = "SELECT * FROM clientes WHERE id = :id";
        $stmt = $adapter->createStatement($sql);
        $result = $stmt->execute(['id' => $id])->current();

        if (!$result) {
            return new JsonModel(['ok'=>false,'error'=>'Cliente não encontrado']);
        }

        return new JsonModel(['ok'=>true,'client'=>$result]);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
    }
}

    public function updateAction()
    {
        try {
        $user = JwtMiddleware::validateToken();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id <= 0) {
            return new JsonModel(['ok'=>false,'error'=>'ID inválido']);
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            return new JsonModel(['ok'=>false,'error'=>'Nenhum dado enviado']);
        }

        $adapter = Db::adapter();
        $sql = "UPDATE clientes 
                SET nome = :nome, email = :email, cnpj = :cnpj, telefone = :telefone, endereco = :endereco
                WHERE id = :id";
        $stmt = $adapter->createStatement($sql);
        $stmt->execute([
            'id' => $id,
            'nome' => $data['nome'] ?? '',
            'email' => $data['email'] ?? '',
            'cnpj' => $data['cnpj'] ?? '',
            'telefone' => $data['telefone'] ?? '',
            'endereco' => $data['endereco'] ?? ''
        ]);

        return new JsonModel(['ok'=>true,'message'=>'Cliente atualizado com sucesso']);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
    }
}

    public function deleteAction()
    {
            try {
        $user = JwtMiddleware::validateToken();
        $id = (int) $this->params()->fromRoute('id', 0);

        if ($id <= 0) {
            return new JsonModel(['ok'=>false,'error'=>'ID inválido']);
        }

        $adapter = Db::adapter();
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $adapter->createStatement($sql);
        $stmt->execute(['id' => $id]);

        return new JsonModel(['ok'=>true,'message'=>'Cliente removido com sucesso']);

    } catch (\Exception $e) {
        return new JsonModel(['ok'=>false,'error'=>$e->getMessage()]);
    }
}

}
