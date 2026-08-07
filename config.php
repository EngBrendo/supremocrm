<?php

//caminho raíz da url
define("DIRURL", "http://{$_SERVER['HTTP_HOST']}/");

//diretório interno do servidor
define("DIRINTERNO", "{$_SERVER['DOCUMENT_ROOT']}/");

//diretório público de imagens
define("DIRIMGPUBROOT", DIRURL."public/imgs/");

/**
 * carrega variáveis via env
 * @method loadEnv
 * @return void
 */
function loadEnv(string $path){
    if (!file_exists($path)) {
        throw new Exception(".env não encontrado");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        $_ENV[$name] = $value;
        putenv("$name=$value");
    }
}

loadEnv(__DIR__ . '/.env');

define("HOST", getenv("HOST"));
define("DB", getenv("DB"));
define("USER", getenv("USER"));
define("PASS", getenv("PASS"));