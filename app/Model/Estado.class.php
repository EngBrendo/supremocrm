<?php

namespace app\Model;

use src\traits\GetSet;
use src\classes\db\Table;
use PDO;

class Estado {

    use GetSet;

    /**
     * ID do registro no banco
     * @var int
     */
    private $id = null;

    /**
     * Código Uf do estado
     * @var int
     */
    private $codigoUf = null;

    /**
     * Nome do estado
     * @var string
     */
    protected $nome = null;

    /**
     * UF do estado
     * @var string
     */
    protected $uf = null;

    /**
     * Região do estado
     * @var int
     */
    private $regiao = null;


    /**
     * Responsável por retornar as propriedades da classe x Colunas do DB
     * @method getProperties
     * @return void
     */
    public function getProperties(){
        return [
            'id'       => 'id',
            'nome'     => 'nome',
            'codigoUf' => 'codigo_uf',
            'uf'       => 'uf',
            'regiao'   => 'regiao'
        ];
    }

    /**
     * Busca um Estado pelo ID
     * @method getEstado
     * @param int $id
     * @return Estado|false
     */
    public static function getEstado($id){
        if(!is_numeric($id)) return false;
        return (new Table('estado'))->select('id = '.$id)->fetchObject(self::class);
    }


    /**
     * Busca todos os Estados no banco
     * @method getEstados
     * @return array
     */
    public static function getEstados(){
        return (new Table('estado'))->select(null, 'uf ASC')->fetchAll(PDO::FETCH_CLASS, self::class);
    }

}