<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class HealthController extends AbstractActionController
{
    public function indexAction()
    {
        return new JsonModel([
            'ok'   => true,
            'time' => date(DATE_ATOM),
            'app'  => 'Sistema de Vendas'
        ]);
    }
}
