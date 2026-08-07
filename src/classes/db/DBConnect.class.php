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
            return new PDO('mysql:host='.HOST.';dbname='.DB.';charset=utf8mb4', USER, PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }catch(PDOException $e){
            return null;
        }
    }

}
