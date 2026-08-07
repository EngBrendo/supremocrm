<?php

namespace app\Model;

use src\classes\db\Table;
use src\traits\GetSet;
use PDO;
use src\classes\mvc\RenderTemplate;
use src\classes\utils\Utils;

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
        $query = 'SELECT a.*, b.nome AS nomeCidade, b.uf, c.nome AS nomeEstado FROM contato a
                INNER JOIN cidade b ON b.id = a.id_cidade
                INNER JOIN estado c ON c.id = a.id_estado
                ORDER BY a.nome ASC';

        return (new Table('contato'))->query($query)->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Busca um contato pelo ID
     * @method getContato
     * @param int $id
     * @return Contato|false
     */
    public static function getContato($id){
        if(!is_numeric($id)) return false;
        return (new Table('contato'))->select('id = :id', ['id' => $id])->fetchObject(self::class);
    }

    /**
     * Busca contatos pelo nome
     * @method getContatosPorNome
     * @param string $nome
     * @return string
     */
    public static function getContatosPorNome($nome){
        if(!strlen($nome)) return '';

        $query = 'SELECT a.*, b.nome AS nomeCidade, b.uf, c.nome AS nomeEstado FROM contato a
                INNER JOIN cidade b ON b.id = a.id_cidade
                INNER JOIN estado c ON c.id = a.id_estado
                WHERE a.nome LIKE :nome
                ORDER BY a.nome ASC';

        $contatos = (new Table('contato'))->query($query, ['nome' => '%'.$nome.'%'])->fetchAll(PDO::FETCH_CLASS, self::class);

        $retorno = '';

        foreach ($contatos as $key => $value) {
            $variaveisLayout = [
                'id' => $value->id,
                'nome' => $value->nome,
                'telefone' => Utils::formatarTelefone($value->telefone),
                'cidade' => $value->nomeCidade,
                'estado' => $value->nomeEstado,
                'idCidade' => $value->idCidade,
                'idEstado' => $value->idEstado,
                'uf' => $value->uf
            ];

            $retorno .= RenderTemplate::getLayout('contato', $variaveisLayout);
        }

        return $retorno;
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
            'idCidade' => $validacaoContato['cidade']->id,
            'idEstado' => $validacaoContato['estado']->id,
            'uf' => $validacaoContato['estado']->uf
        ];

        $layout = RenderTemplate::getLayout('contato', $variaveisLayout);

        return ['sucesso' => true, 'layout' => $layout, 'mensagem' => ''];
    }

    /**
     * Edita um contato
     * @method editarContato
     * @return array
     */
    public static function editarContato($idContato, $nome, $telefone, $idCidade, $idEstado){
        $telefoneSemMascara = preg_replace("/[^0-9]/", "", trim($telefone));

        $validacaoContato = self::validarDadosContato($nome, $telefoneSemMascara, $idCidade, $idEstado, $idContato);
        if(!$validacaoContato['sucesso']) return $validacaoContato;

        $dadosCadastro = [
            'nome' => $nome,
            'telefone' => $telefoneSemMascara,
            'id_cidade' => $idCidade,
            'id_estado' => $idEstado
        ];

        $obTabela = new Table('contato');

        if(!($obTabela)->update('id = :id', $dadosCadastro, ['id' => $idContato])) return ['sucesso' => false, 'mensagem' => 'Erro na edição das informações.'];

        $variaveisLayout = [
            'id' => $idContato,
            'nome' => $nome,
            'telefone' => $telefone,
            'cidade' => $validacaoContato['cidade']->nome,
            'estado' => $validacaoContato['estado']->nome,
            'idCidade' => $validacaoContato['cidade']->id,
            'idEstado' => $validacaoContato['estado']->id,
            'uf' => $validacaoContato['estado']->uf
        ];

        $layout = RenderTemplate::getLayout('contato', $variaveisLayout);

        return ['sucesso' => true, 'layout' => $layout, 'mensagem' => ''];
    }

    /**
     * Deleta um contato do banco
     * @method validarDadosContato
     * @return array
     */
    public static function validarDadosContato(&$nome, &$telefone, $idCidade, $idEstado, $idContato = null){
        if(!strlen($nome) or !strlen($telefone) or !is_numeric($idCidade) or !is_numeric($idEstado)) return ['sucesso' => false, 'mensagem' => 'Dados inválidos.'];

        $nome = trim($nome);
        if(!preg_match("/^[a-zA-Zà-úÀ-Ú\s]+$/", $nome)) return ['sucesso' => false, 'mensagem' => 'O nome possui caracteres inválidos.'];

        // condição para permitir edição do contato, mantendo o mesmo telefone
        $paramsTelefone = ['telefone' => $telefone];
        $condicaoEdicao = '';

        if(is_numeric($idContato)){
            $contato = Contato::getContato($idContato);
            if(!($contato instanceof Contato)) return ['sucesso' => false, 'mensagem' => 'Contato não encontrado nos registros.'];
            $condicaoEdicao = ' AND id != :idContato';
            $paramsTelefone['idContato'] = $idContato;
        }

        // valida duplicação do telefone
        if((new Table('contato'))->select('telefone = :telefone'.$condicaoEdicao, $paramsTelefone)->rowCount() > 0) return ['sucesso' => false, 'mensagem' => 'Telefone já cadastrado.'];

        $cidade = Cidade::getCidade($idCidade);
        if(! ($cidade instanceof Cidade)) return ['sucesso' => false, 'mensagem' => 'Cidade não encontrada nos registros.'];

        $estado = Estado::getEstado($idEstado);
        if(! ($estado instanceof Estado)) return ['sucesso' => false, 'mensagem' => 'Estado não encontrado nos registros.'];

        if($cidade->uf != $estado->uf) return ['sucesso' => false, 'mensagem' => 'Cidade selecionada não pertence ao Estado selecionado.'];

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
        return (new Table('contato'))->delete('id = :id', ['id' => $idContato]);
    }

}
