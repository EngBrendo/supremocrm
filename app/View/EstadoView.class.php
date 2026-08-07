<?php

namespace app\View;

use src\classes\mvc\RenderTemplate;

/**
 * Concentra a transformação de dados de estado em fragmentos HTML.
 */
class EstadoView{

    /**
     * Monta o layout de opções de Estados
     * @method getLayoutOtionEstados
     * @param array $estados
     * @return string
     */
    public static function getLayoutOtionEstados($estados){
        $retorno = '';

        foreach ($estados as $value) {
            $retorno .= RenderTemplate::getLayout('utils/option', ['value' => $value->id, 'option' => $value->uf, 'selected' => null]);
        }

        return $retorno;
    }

}
