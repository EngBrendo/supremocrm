<?php

require_once("../includes.php");

use app\Model\Cidade;
use app\Controller\ControllerDashboard;

extract($_POST);

if(!isset($uf)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

if(strlen($uf) != 2){
    echo json_encode(['sucesso' => false, 'mensagem' => 'UF inválida.']);
    exit;
}

$cidades = Cidade::getCidadesPorUf($uf);

$retorno = ControllerDashboard::getLayoutOptionCidades($cidades);

echo json_encode(['sucesso' => true, 'data' => $retorno], JSON_UNESCAPED_UNICODE);
exit;