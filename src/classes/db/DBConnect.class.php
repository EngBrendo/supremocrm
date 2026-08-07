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
            return new PDO('mysql:host='.HOST.';dbname='.DB.';charset=utf8mb4', USER, PASS);
        }catch(PDOException $e){
            return null;
        }
    }

}