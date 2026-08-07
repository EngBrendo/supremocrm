<?php

namespace app\Model;

use src\traits\GetSet;
use src\classes\db\Table;
use PDO;

class Cidade {

    use GetSet;

    /**
     * ID do registro no banco
     * @var int
     */
    private $id = null;

    /**
     * Código da cidade
     * @var int
     */
    private $codigo = null;

    /**
     * Nome da cidade
     * @var string
     */
    protected $nome = null;

    /**
     * UF do estado da cidade
     * @var string
     */
    protected $uf = null;


    /**
     * Responsável por retornar as propriedades da classe x Colunas do DB
     * @method getProperties
     * @return void
     */
    public function getProperties(){
        return [
            'id'     => 'id',
            'nome'   => 'nome',
            'codigo' => 'codigo',
            'uf'     => 'uf'
        ];
    }

    /**
     * Busca uma cidade pelo ID
     * @method getCidade
     * @param int $id
     * @return Cidade|false
     */
    public static function getCidade($id){
        if(!is_numeric($id)) return false;
        return (new Table('cidade'))->select('id = :id', ['id' => $id])->fetchObject(self::class);
    }

    /**
     * Busca cidades por Uf
     * @method getCidadesPorUf
     * @param string $uf
     * @return array
     */
    public static function getCidadesPorUf($uf){
        if(!strlen($uf)) return [];
        return (new Table('cidade'))->select('uf = :uf', ['uf' => $uf], 'nome ASC')->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca cidades por condição
     * @method getCidadesPorCondicao
     * @param array $condicoes Pares coluna => valor permitidos para filtrar a consulta
     * @return array
     */
    public static function getCidadesPorCondicao(array $condicoes){
        if(empty($condicoes)) return [];

        $camposPermitidos = ['id', 'nome', 'codigo', 'uf'];
        $where = [];
        $params = [];
        foreach ($condicoes as $campo => $valor) {
            if (!in_array($campo, $camposPermitidos, true)) return [];
            $where[] = '`'.$campo.'` = :'.$campo;
            $params[$campo] = $valor;
        }

        return (new Table('cidade'))->select(implode(' AND ', $where), $params)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

}
