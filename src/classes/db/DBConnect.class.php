<?php

namespace src\classes\db;

use PDO;
use PDOException;

/**
 * classe de conexão com o banco
 */
class DBConnect{

    protected function getConnection(){
        try{
            return new PDO('mysql:host='.HOST.';dbname='.DB, USER, PASS);
        }catch(PDOException $e){
            echo '<pre>'; print_r($e); echo '</pre>'; exit;
            return null;
        }
    }

}