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
     * ID inserido
     * @var integer
     */
    private $lastInsertId  = null;

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
    public function execute($query, $params = []){
        self::$conexao = $this->getConnection();

        $result = null;

        if(self::$conexao == null) return $result;

        try {
            $statement = self::$conexao->prepare($query);
            $result = $statement->execute($params);
            $this->lastInsertId = self::$conexao->lastInsertId();
        }catch (Exception $e) {
            $result = false;
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
    public function query($query, $params = []){
        self::$conexao = $this->getConnection();

        $result = null;

        if(self::$conexao == null) return $result;

        try {
            $result = self::$conexao->prepare($query);
            $result->execute($params);
        }catch (Exception $e) {
            $result = false;
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
        if (empty($dados)) return false;

        $campos = array_keys($dados);
        foreach ($campos as $campo) {
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $campo)) return false;
        }

        $camposSql = '`'.implode('`,`', $campos).'`';
        $placeholders = [];
        $params = [];
        foreach ($dados as $campo => $valor) {
            $placeholder = ':insert_'.$campo;
            $placeholders[] = $placeholder;
            $params['insert_'.$campo] = $valor;
        }

        $query = "INSERT INTO `".$this->table."` (".$camposSql.") VALUES (".implode(',', $placeholders).")";

        return $this->execute($query, $params);
    }

    /**
     * realiza um select em qualquer tabela do banco
     *
     * @method select
     * @param  string $where cláusula WHERE do SQL
     * @param  array $params Valores nomeados usados na cláusula WHERE
     * @param  string $order cláusula ODER BY do SQL
     * @return PDOStatement
     */
    public function select($where = null, $params = [], $order = null){
        $where = strlen($where) ? (" WHERE " . $where) : '';
        $order = strlen($order) ? (" ORDER BY " . $order) : '';
        $query = "SELECT * FROM `" . $this->table . '`' . $where . $order;

        return $this->query($query, $params);
    }

  /**
   * Responsável por criar a query de exclusão (DELETE)
   * @method delete
   * @param  string $where Instrução WHERE do Delete
   * @return boolean
   */
  public function delete($where = null, $params = []){
    if(!is_string($where) or !strlen($where)) return false;

    $query = "DELETE FROM `".$this->table."` WHERE ".$where;

    return $this->execute($query, $params);
  }

  /**
   * Responsável por retornar o último ID inserido
   * @method getLastInsertId
   * @return int
   */
  public function getLastInsertId(){
    return $this->lastInsertId;
  }

  /**
   * Método responsável por criar a query de atualização (UPDATE)
   * @method update
   * @param  string $where Instrução WHERE do Delete
   * @param  mixed  $dados Array ou Objeto (objeto deve possuir o método getAllAttributes do trait GetSet)
   * @param  array  $whereParams Valores nomeados usados na cláusula WHERE
   * @return boolean
   */
  public function update($where = null, $dados = null, $whereParams = []){
    if (!is_string($where) or !strlen($where)) return false;

    if (is_object($dados)) {
      if (!method_exists ($dados, 'getAllAttributes')) return false;
      $dados = $dados->getAllAttributes(true);
    }

    if (!is_array($dados) || empty($dados)) return false;

    $valores = [];
    $params = $whereParams;
    foreach($dados as $key => $value){
      if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)) return false;
      $paramName = 'update_'.$key;
      $valores[] = "`".$key."` = :".$paramName;
      $params[$paramName] = $value;
    }
    $valores = implode(',',$valores);
    $query   = "UPDATE `".$this->table."` SET ".$valores." WHERE ".$where;
    return $this->execute($query, $params);
  }

}
