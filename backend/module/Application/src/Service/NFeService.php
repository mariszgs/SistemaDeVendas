<?php
namespace Application\Service;

use GuzzleHttp\Client;

class NfeService
{
    private $client;
    private $apiUrl;
    private $token;

    public function __construct()
    {
        $this->apiUrl = 'https://homologacao.focusnfe.com.br/v2/';
        $this->token = 'SEU_TOKEN_DE_TESTE_AQUI'; // coloque o token de teste aqui

        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 10.0,
        ]);
    }

    public function emitirNfe(array $dados)
    {
        try {
            $response = $this->client->post("nfe?ref={$dados['ref']}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}",
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json'
                ],
                'json' => $dados
            ]);

            $body = json_decode($response->getBody(), true);
            return $body;

        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
