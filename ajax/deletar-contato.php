<?php

require_once("../includes.php");

use app\Model\Contato;
use src\classes\security\Csrf;

if (!Csrf::validate($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '')) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida. Atualize a página e tente novamente.']);
    exit;
}

extract($_POST);

if(!isset($idContato) or !is_numeric($idContato)){
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incorretos.']);
    exit;
}

$sucesso = Contato::deletarContato($idContato);

echo json_encode(['sucesso' => $sucesso, 'mesagem' => ($sucesso ? '' : 'Erro ao deletar contato.')]);
exit;
