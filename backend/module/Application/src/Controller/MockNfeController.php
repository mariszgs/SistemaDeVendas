<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class MockNfeController extends AbstractActionController
{
    public function emitirAction()
    {
        $rawBody = file_get_contents('php://input');
error_log("RAW BODY => " . $rawBody);

        $dadosPedido = json_decode(file_get_contents('php://input'), true);

$pedidoId = $dadosPedido['pedido_id'] ?? null;

if (!$pedidoId) {
    return new JsonModel([
        "status" => "erro",
        "mensagem" => "Pedido não informado no corpo da requisição."
    ]);
}
        $clienteId = $dadosPedido['cliente_id'] ?? null;
        $total = $dadosPedido['total'] ?? 0;
        $itens = $dadosPedido['itens'] ?? [];

        $chave = substr(md5($pedidoId . time()), 0, 44);

        return new JsonModel([
            "status" => "processando_autorizacao",
            "uuid" => uniqid(),
            "ref" => "VENDA-" . $pedidoId,
            "cStat" => "100",
            "mensagem" => "Autorizado o uso da NF-e",
            "nfe" => [
                "chave" => $chave,
                "numero" => $pedidoId,
                "serie" => "1",
                "cliente_id" => $clienteId,
                "valor_total" => $total,
                "qtd_itens" => count($itens),
                "url_xml" => "http://sdv.local/xml/nfe-" . $pedidoId . ".xml",
                "url_danfe" => "http://sdv.local/danfe/nfe-" . $pedidoId . ".pdf"
            ]
        ]);
    }

    public function consultarAction()
    {
        $pedidoId = $this->params()->fromRoute('id', null);

        if (!$pedidoId) {
            return new JsonModel([
                "status" => "erro",
                "mensagem" => "Pedido não informado."
            ]);
        }

        return new JsonModel([
            "status" => "autorizado",
            "cStat" => "100",
            "mensagem" => "Nota Fiscal Eletrônica autorizada",
            "chave" => substr(md5($pedidoId), 0, 44)
        ]);
    }
}


