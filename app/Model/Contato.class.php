<?php

namespace app\Model;

use src\classes\db\Table;
use src\traits\GetSet;
use PDO;
use src\classes\mvc\RenderTemplate;

class Contato {

    use GetSet;

    /**
     * ID do registro no banco
     * @var int
     */
    private $id = null;

    /**
     * Nome do contato
     * @var string
     */
    protected $nome = null;

    /**
     * Telefone do contato
     * @var string
     */
    protected $telefone = null;

    /**
     * ID do estado
     * @var int
     */
    private $idEstado = null;

    /**
     * ID do da cidade
     * @var int
     */
    private $idCidade = null;

    /**
     * Data do cadastro do contato
     * @var string
     */
    protected $dataCadastro = null;

    /**
     * Responsável por retornar as propriedades da classe x Colunas do DB
     * @method getProperties
     * @return void
     */
    public function getProperties(){
        return [
            'id'           => 'id',
            'nome'         => 'nome',
            'telefone'     => 'telefone',
            'idEstado'     => 'id_estado',
            'idCidade'     => 'id_cidade',
            'dataCadastro' => 'data_cadastro',
        ];
    }

    /**
     * Busca todos os contatos no banco
     * @method getContatos
     * @return array
     */
    public static function getContatos(){
        $query = 'SELECT a.*, b.nome AS nomeCidade, c.nome AS nomeEstado  FROM contato a
                INNER JOIN cidade b ON b.id = a.id_cidade
                INNER JOIN estado c ON c.id = a.id_estado
                ORDER BY a.nome ASC';

        return (new Table('contato'))->query($query)->fetchAll(PDO::FETCH_CLASS, self::class);
    }


    /**
     * Cadastra um contato
     * @method cadastrarContato
     * @return array
     */
    public static function cadastrarContato($nome, $telefone, $idCidade, $idEstado){
        $telefoneSemMascara = preg_replace("/[^0-9]/", "", trim($telefone));

        $validacaoContato = self::validarDadosContato($nome, $telefoneSemMascara, $idCidade, $idEstado);
        if(!$validacaoContato['sucesso']) return $validacaoContato;

        $dadosCadastro = [
            'nome' => $nome,
            'telefone' => $telefoneSemMascara,
            'id_cidade' => $idCidade,
            'id_estado' => $idEstado
        ];

        $obTabela = new Table('contato');

        if(!($obTabela)->insert($dadosCadastro)) return ['sucesso' => false, 'mensagem' => 'Erro no cadastro das informações.'];

        $variaveisLayout = [
            'id' => $obTabela->getLastInsertId(),
            'nome' => $nome,
            'telefone' => $telefone,
            'cidade' => $validacaoContato['cidade']->nome,
            'estado' => $validacaoContato['estado']->nome, 
            'dataCadastro' => date("d-m-Y H:m:s", time())
        ];

        $layout = RenderTemplate::getLayout('contato', $variaveisLayout);

        return ['sucesso' => true, 'layout' => $layout, 'mensagem' => ''];
    }

    /**
     * Deleta um contato do banco
     * @method validarDadosContato
     * @param int $idContato
     * @return array
     */
    public static function validarDadosContato(&$nome, &$telefone, $idCidade, $idEstado){
        if(!strlen($nome) or !strlen($telefone) or !is_numeric($idCidade) or !is_numeric($idEstado)) return ['sucesso' => false, 'mensagem' => 'Dados inválidos.'];

        $nome = trim($nome);
        if(!preg_match("/^[a-zA-Zà-úÀ-Ú\s]+$/", $nome)) return ['sucesso' => false, 'mensagem' => 'O nome possui caracteres inválidos.'];

        // valida duplicação do telefone
        if((new Table('contato'))->select('telefone = "'.$telefone.'"')->rowCount() > 0) return ['sucesso' => false, 'mensagem' => 'Telefone já cadastrado.'];

        $cidade = Cidade::getCidade($idCidade);
        if(! ($cidade instanceof Cidade)) return ['sucesso' => false, 'mensagem' => 'Cidade não encontrada nos registros.'];

        $estado = Estado::getEstado($idEstado);
        if(! ($estado instanceof Estado)) return ['sucesso' => false, 'mensagem' => 'Estado não encontrada nos registros.'];

        return ['sucesso' => true, 'cidade' => $cidade, 'estado' => $estado];
    }

    /**
     * Deleta um contato do banco
     * @method deletarContato
     * @param int $idContato
     * @return array
     */
    public static function deletarContato($idContato){
        if(!is_numeric($idContato)) return false;
        return (new Table('contato'))->delete('id = '.$idContato);
    }

}