<?php

namespace app\View;

use src\classes\mvc\RenderTemplate;

/**
 * Concentra a transformação de dados de cidade em fragmentos HTML.
 */
class CidadeView{

    /**
     * Monta o layout de opções de Cidade
     * @method getLayoutOptionCidades
     * @param array $cidades
     * @return string
     */
    public static function getLayoutOptionCidades($cidades){
        $retorno = '';

        foreach ($cidades as $value) {
            $retorno .= RenderTemplate::getLayout('utils/option', ['value' => $value->id, 'option' => $value->nome, 'selected' => null]);
        }

        return $retorno;
    }

}
