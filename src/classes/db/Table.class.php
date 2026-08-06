<?php

namespace src\classes\db;

use src\classes\db\DBConnect;
use Exception;
use PDO;
use PDOStatement;

/**
 * classe que realiza as operações no banco
 */
class Table extends DBConnect{

    /**
     * Propriedade de conexão com o banco de dados
     * @var PDO
     */
    private static $conexao = false;

    /**
     * Nome da tabela
     * @var string
     */
    private $table = null;

    /**
     * Construtor responsável por definir os valores das propriedades da tabela
     * @method __construct
     * @param  string $table Nome da tabela no banco de dados
     */
    public function __construct($table = null){
        $this->table = $table;
    }

    /**
     * Resposável por executar uma query no banco utilizando o método execute do PDO
     * @method execute
     * @param  string $query Query que será executada
     */
    public function execute($query){
        self::$conexao = $this->getConnection();

        $result = null;

        if(self::$conexao == null) return $result;

        try {
            $result = self::$conexao->prepare($query)->execute();
        }catch (Exception $e) {
            self::$conexao->rollBack();
        }

        //FECHA A CONEXÃO
        self::$conexao = null;
      
        return $result;
    }

    /**
     * Resposável por executar uma query no banco utilizando o método query do PDO
     * @method query
     * @param  string $query Query que será executada
     */
    public function query($query){
        self::$conexao = $this->getConnection();

        $result = null;

        if(self::$conexao == null) return $result;

        try {
            $result = self::$conexao->query($query);
        }catch (Exception $e) {
            self::$conexao->rollBack();
        }

        //FECHA A CONEXÃO
        self::$conexao = null;
      
        return $result;
    }

    /**
     * realiza um insert em qualquer tabela do banco
     *
     * @param array $dados dados que serão inseridos no banco. No formato key => value
     * @return boolean
     */
    public function insert($dados){
        $valores = [];
        foreach (array_values($dados) as $value) {
            $valores[] = "'".$value."'";
        }

        $campos  = implode('`,`', array_keys($dados));
        $valores = implode(",", $valores);
        
        $query = "INSERT INTO ".$this->table." (`".$campos."`) VALUES (".$valores.")";

        return $this->execute($query);
    }

    /**
     * realiza um select em qualquer tabela do banco
     *
     * @method select
     * @param  string $where cláusula WHERE do SQL
     * @param  string $order cláusula ODER BY do SQL
     * @return PDOStatement
     */
    public function select($where = null, $order = null){
        $where = strlen($where) ? (" WHERE " . $where) : '';
        $order = strlen($order) ? (" ORDER BY " . $order) : '';
        $query = "SELECT * FROM " . $this->table . $where . $order;

        return $this->query($query);
    }

  /**
   * Responsável por criar a query de exclusão (DELETE)
   * @method delete
   * @param  string $where Instrução WHERE do Delete
   * @return boolean
   */
  public function delete($where = null){
    if(!is_string($where) or !strlen($where)) return false;

    $query = "DELETE FROM ".$this->table." WHERE ".$where;
    
    return $this->execute($query);
  }

}