<?php
namespace Application\Middleware;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware extends AbstractActionController
{
    public static function validateToken(): array
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            throw new \Exception('Token não enviado');
        }

        $authHeader = $headers['Authorization'];
        if (strpos($authHeader, 'Bearer ') !== 0) {
            throw new \Exception('Token inválido');
        }

        $token = substr($authHeader, 7);

        try {
            $decoded = JWT::decode($token, new Key('SUA_CHAVE_SECRETA', 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) {
            throw new \Exception('Token inválido ou expirado');
        }
    }
}
