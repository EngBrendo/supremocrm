<?php

require_once("../includes.php");

use app\Model\Contato;

extract($_POST);

if(!isset($idContato) or !is_numeric($idContato)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

$sucesso = Contato::deletarContato($idContato);

echo json_encode(['sucesso' => $sucesso, 'mesagem' => ($sucesso ? '' : 'Erro ao deletar contato.')]);
exit;