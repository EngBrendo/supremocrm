<?php 

/**
 * realiza o autoload das classes e traits
 *
 * @param string $namespace
 * @return void
 */
function autoload($namespace){

    $sufixo = '.class';

    if(strpos($namespace, 'trait') !== false){
        $sufixo = '.trait';
    }

    $classe = __DIR__.'/../'.str_replace('\\', '/', strval($namespace)) . $sufixo . '.php';

    if (file_exists($classe) && !is_dir($classe)) {
        include_once $classe;
    }

}

spl_autoload_register('autoload');