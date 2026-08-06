<?php

namespace src\classes\mvc;

/**
 * faz o controle das rotas
 */
class Routes{

    //rotas disponíveis
    private $rota = [
        ''           => 'ControllerDashboard',
        'dashboard'  => 'ControllerDashboard'
    ];

    /**
     * busca rotas e parâmetros na url
     * @method getRoute
     * @return string nome do controlador responsável pela rota
     */
    public function getRoute(){
        $parametrosGet = explode('/', filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));

        //verifica se a rota existe
        if(!array_key_exists($parametrosGet[1], $this->rota)) return 'ControllerDashboard';
        
        //retorna o controlador correspondente
        return (file_exists(DIRINTERNO.'app/Controller/'.$this->rota[$parametrosGet[1]].'.class.php')) ? $this->rota[$parametrosGet[1]] : 'ControllerDashboard';
    }
}