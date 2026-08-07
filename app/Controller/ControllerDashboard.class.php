<?php

namespace app\Controller;

use app\Model\Cidade;
use src\classes\mvc\RenderTemplate;
use app\Model\Contato;
use app\Model\Estado;
use src\classes\utils\Utils;

/**
 * controlador resposável pela visualização da home
 */
class ControllerDashboard{

    public function __construct(){
        $contatos = Contato::getContatos();

        $variaveisLayout['contatos']    = $this->getLayoutContatos($contatos);
        $variaveisLayout['tableHidden'] = empty($contatos) ? 'visible' : '';

        $estados = Estado::getEstados();
        $variaveisLayout['optionEstados'] = $this->getLayoutOtionEstados($estados);

        //carrega o conteúdo que será aplicado ao template
        $variaveisTemplate['conteudo'] = RenderTemplate::getLayout('dashboard', $variaveisLayout);

        RenderTemplate::setTemplate($variaveisTemplate);
    }


    /**
     * Monta o layout de opções de Estados
     * @method getLayoutOtionEstados
     * @param array $estados
     * @return string
     */
    private function getLayoutOtionEstados($estados){
        $retorno = '';

        foreach ($estados as $value) {
            $retorno .= RenderTemplate::getLayout('utils/option', ['value' => $value->id, 'option' => $value->uf, 'selected' => null]);
        }

        return $retorno;
    }

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

    /**
     * Monta o layout de listagem de contatos
     * @method getLayoutContatos
     * @param array $contatos
     * @return string
     */
    private function getLayoutContatos($contatos){
        $retorno = '';

        foreach ($contatos as $value) {
            $dadosLayout = [
                'id' => $value->id,
                'nome' => $value->nome, 
                'telefone' => Utils::formatarTelefone($value->telefone),
                'cidade' => $value->nomeCidade, 
                'estado' => $value->nomeEstado, 
                'dataCadastro' => date("d-m-Y", strtotime($value->dataCadastro))
            ];

            $retorno .= RenderTemplate::getLayout('contato', $dadosLayout);
        }

        return $retorno;
    }

}