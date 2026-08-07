<?php

require_once("../includes.php");

use app\Model\Contato;
use app\View\ContatoView;

extract($_POST);

if(!isset($busca) or !strlen($busca)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

$contatos = Contato::getContatosPorNome($busca);

echo json_encode(['sucesso' => true, 'layout' => ContatoView::renderList($contatos)]);
exit;
