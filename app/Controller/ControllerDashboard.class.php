<?php

namespace app\Controller;

use src\classes\mvc\RenderTemplate;
use app\Model\Contato;
use app\Model\Estado;
use app\View\ContatoView;
use app\View\EstadoView;

/**
 * controlador resposável pela visualização da home
 */
class ControllerDashboard{

    public function __construct(){
        $contatos = Contato::getContatos();

        $estados = Estado::getEstados();
        $variaveisLayout['optionEstados'] = EstadoView::getLayoutOtionEstados($estados);

        $variaveisLayout['tipoAcao']            = 'Cadastro';
        $variaveisLayout['formContatoCadastro'] = RenderTemplate::getLayout('form-contato', $variaveisLayout, ['optionEstados']);

        $variaveisLayout['tipoAcao']          = 'Edicao';
        $variaveisLayout['formContatoEdicao'] = RenderTemplate::getLayout('form-contato', $variaveisLayout, ['optionEstados']);

        $variaveisLayout['contatos']    = ContatoView::renderList($contatos);
        $variaveisLayout['tableHidden'] = empty($contatos) ? 'visible' : '';

        //carrega o conteúdo que será aplicado ao template
        $variaveisTemplate['conteudo'] = RenderTemplate::getLayout(
            'dashboard',
            $variaveisLayout,
            ['formContatoCadastro', 'formContatoEdicao', 'contatos']
        );

        RenderTemplate::setTemplate($variaveisTemplate);
    }

}
