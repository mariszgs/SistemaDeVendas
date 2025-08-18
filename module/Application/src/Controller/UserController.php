<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Shared\Db;
use Firebase\JWT\JWT;

class UserController extends AbstractActionController
{
    public function registerAction()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['nome'], $data['email'], $data['senha'])) {
            return new JsonModel(['error' => 'Campos obrigatórios faltando']);
        }

        $nome = $data['nome'];
        $email = $data['email'];
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

        $adapter = Db::adapter();
        $sql = "INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)";

        try {
            $stmt = $adapter->createStatement($sql);
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha_hash' => $senhaHash
            ]);

            return new JsonModel(['ok' => true, 'message' => 'Usuário cadastrado com sucesso']);
        } catch (\Exception $e) {
            return new JsonModel(['error' => $e->getMessage()]);
        }
    }

    public function loginAction()
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email'], $data['senha'])) {
        return new JsonModel(['error' => 'Email e senha obrigatórios']);
    }

    $adapter = Db::adapter();
    $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
    $stmt = $adapter->createStatement($sql);
    $result = $stmt->execute(['email' => $data['email']]);
    $user = $result->current();

    if (!$user || !password_verify($data['senha'], $user['senha_hash'])) {
        return new JsonModel(['error' => 'Email ou senha inválidos']);
    }

    $payload = [
        'sub' => $user['id'],
        'nome' => $user['nome'],
        'email' => $user['email'],
        'iat' => time(),
        'exp' => time() + 3600 // 1 hora
    ];

    $jwt = JWT::encode($payload, 'SUA_CHAVE_SECRETA', 'HS256');

    return new JsonModel(['ok' => true, 'token' => $jwt]);
}
}
