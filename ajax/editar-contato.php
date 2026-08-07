<?php

require_once("../includes.php");

use app\Model\Contato;
use app\View\ContatoView;

extract($_POST);

if(!is_numeric($id) or !strlen($nome) or !strlen($telefone) or !is_numeric($idCidade) or !is_numeric($idEstado)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

$retorno = Contato::editarContato($id, $nome, $telefone, $idCidade, $idEstado);
if ($retorno['sucesso']) {
    $retorno['layout'] = ContatoView::renderContato($retorno['contato']);
    unset($retorno['contato']);
}

echo json_encode($retorno);
exit;
