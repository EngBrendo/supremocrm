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
                INNER JOIN estado c ON c.id = a.id_estado';

        return (new Table('contato'))->query($query)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca um contato no banco pelo nis
     * @method getContato
     * @return array
     */
    public static function getContato($nis){
        if(!is_string($nis) or strlen($nis) != 11 or !preg_match('/^[0-9]+$/', $nis)) return ['sucesso' => false, 'mensagem' => 'NIS inválido.'];

        $obContato = (new Table('contato'))->select('nis = "'.$nis.'"')->fetchObject(self::class);

        if(!($obContato instanceof Contato)) return ['suecsso' => false, 'mensagem' => 'Contato não não encontrado.'];

        return ['sucesso' => true, 'nome' => $obContato->nome];
    }

    /**
     * Cadastra um contato
     * @method cadastrarContato
     * @return array
     */
    public function cadastrarContato($nome){
        if(!is_string($nome)) return ['sucesso' => false, 'mensagem' => 'Nome inválido.'];

        $nome = trim($nome);

        if(!strlen($nome)) return ['sucesso' => false, 'mensagem' => 'Nome inválido.'];

        if(!preg_match("/^[a-zA-Zà-úÀ-Ú\s]+$/", $nome)) return ['sucesso' => false, 'mensagem' => 'O nome possui caracteres inválidos.'];

        $obTabela = (new Table('contato'));

        $maxTentativas = 50;
        $numTentativas = 0;

        $nis = self::gerarNis();
        while($obTabela->select('nis = "'.$nis.'"')->rowCount() > 0){
            if($numTentativas >= $maxTentativas) return ['sucesso' => false, 'mensagem' => 'Problemas ao gerar NIS do contato.'];
            $nis = self::gerarNis();
        }

        if(!$obTabela->insert(['nome' => $nome, 'nis' => $nis])) return ['sucesso' => false, 'mensagem' => 'Erro no cadastro das informações.'];

        $variaveisLayout = [
            'nome' => $nome,
            'nis'  => $nis,
            'dataCadastro' => date("d-m-Y H:m:s", time())
        ];

        $layout = RenderTemplate::getLayout('contato', $variaveisLayout);

        return ['sucesso' => true, 'nis' => $nis, 'layout' => $layout, 'mensagem' => ''];
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