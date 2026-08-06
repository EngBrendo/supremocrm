<?php 

namespace src\traits;

/**
 * trait para 'renderizar' o template de acordo com a view correspondente
 */
trait RenderTemplate{

    /**
     * inclui o arquivo de template
     *
     * @method setTemplate
     * @param  array $variaveis variaveis que serão inseridas no layout do template padrão
     */
    public function setTemplate($variaveis = null){
        $variaveisTemplate = [
            'conteudo' => $variaveis['conteudo'] ?? null,
            'mensagem' => $variaveis['mensagem-alerta'] ?? null,
            'cssPath'  => DIRURL . "public/css/style.css",
            'jsPath'   => DIRURL . "public/js/javascript.js",
            'logoPath' => DIRIMGPUBROOT . "logo.png",
            'siteUrl'  => DIRURL.'dashboard',
            'ano'      => date("Y")
        ];

        echo $this->getLayout('template', $variaveisTemplate);
    }

    /**
     * retorna um arquivo de layout com as variáveis carregadas
     *
     * @method getLayout
     * @param  string $fileName   nome do arquivo de layout
     * @param  array  $variaveis  variaveis que serão inseridas no layout
     */
    public function getLayout($fileName, $variaveis = []){
        $template = file_get_contents(DIRINTERNO.'app/View/' . $fileName . '.php');

        foreach($variaveis as $variavel => $valor){
            $view = '{{'.$variavel.'}}';
            if(is_array($valor)) continue;
            $template = str_replace($view, $valor, $template);
        }

        return $template;
    }
}
