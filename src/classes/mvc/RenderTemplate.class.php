<?php 

namespace src\classes\mvc;

/**
 * classe para 'renderizar' o template de acordo com a view correspondente
 */
class RenderTemplate{

    /**
     * inclui o arquivo de template
     *
     * @method setTemplate
     * @param  array $variaveis variaveis que serão inseridas no layout do template padrão
     */
    public static function setTemplate($variaveis = null){
        $variaveisTemplate = [
            'conteudo' => $variaveis['conteudo'] ?? null,
            'mensagem' => $variaveis['mensagem-alerta'] ?? null,
            'cssPath'  => DIRURL . "public/css/style.css",
            'jsPath'   => DIRURL . "public/js/script.js",
            'logoPath' => DIRIMGPUBROOT . "logo.png",
            'siteUrl'  => DIRURL.'dashboard',
            'ano'      => date("Y")
        ];

        echo self::getLayout('template', $variaveisTemplate, ['conteudo']);
    }

    /**
     * retorna um arquivo de layout com as variáveis carregadas
     *
     * @method getLayout
     * @param  string $fileName   nome do arquivo de layout
     * @param  array  $variaveis  variaveis que serão inseridas no layout
     * @param  array  $rawVariables Variáveis que contêm fragmentos HTML internos já renderizados
     */
    public static function getLayout($fileName, $variaveis = [], $rawVariables = []){
        $template = file_get_contents(DIRINTERNO.'app/View/layout/' . $fileName . '.php');

        foreach($variaveis as $variavel => $valor){
            $view = '{{'.$variavel.'}}';
            if(is_array($valor)) continue;
            $valor = in_array($variavel, $rawVariables, true)
                ? (string) $valor
                : htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $template = str_replace($view, $valor, $template);
        }

        return $template;
    }
}
