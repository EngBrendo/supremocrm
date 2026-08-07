<?php

require_once("../includes.php");

use app\Model\Contato;

extract($_POST);

if(!isset($busca) or !strlen($busca)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

echo json_encode(['sucesso' => true, 'layout' => Contato::getContatosPorNome($busca)]);
exit;