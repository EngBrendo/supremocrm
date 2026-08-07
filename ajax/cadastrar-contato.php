<?php

require_once("../includes.php");

use app\Model\Contato;

extract($_POST);

if(!strlen($nome) or !strlen($telefone) or !is_numeric($idCidade) or !is_numeric($idEstado)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

echo json_encode(Contato::cadastrarContato($nome, $telefone, $idCidade, $idEstado));
exit;