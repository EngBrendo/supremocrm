<?php

namespace app\View;

use src\classes\mvc\RenderTemplate;
use src\classes\utils\Utils;

/**
 * Concentra a transformação de dados de contato em fragmentos HTML.
 */
class ContatoView{

    /**
     * Renderiza as linhas de uma lista de contatos.
     *
     * @param array $contatos
     * @return string
     */
    public static function renderList(array $contatos){
        $layout = '';

        foreach ($contatos as $contato) {
            $layout .= self::renderContato($contato);
        }

        return $layout;
    }

    /**
     * Renderiza uma linha de contato a partir de um objeto ou array de dados.
     *
     * @param mixed $contato
     * @return string
     */
    public static function renderContato($contato){
        return RenderTemplate::getLayout('contato', [
            'id' => self::getValue($contato, 'id'),
            'nome' => self::getValue($contato, 'nome'),
            'telefone' => Utils::formatarTelefone(self::getValue($contato, 'telefone')),
            'cidade' => self::getValue($contato, 'nomeCidade'),
            'estado' => self::getValue($contato, 'nomeEstado'),
            'idEstado' => self::getValue($contato, 'idEstado'),
            'idCidade' => self::getValue($contato, 'idCidade'),
            'uf' => self::getValue($contato, 'uf')
        ]);
    }

    /**
     * Obtém um campo de uma estrutura de dados
     */
    private static function getValue($contato, $campo){
        if (is_array($contato)) return $contato[$campo] ?? null;

        return $contato->$campo ?? null;
    }

}
