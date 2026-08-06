<?php

namespace src\classes\mvc;

use src\classes\mvc\Routes;

/**
 * Inicia o controlador correspondete à rota passada pela url
 */
class ControllerRoutes extends Routes{

    public function __construct(){
        $this->initController();
    }

    /**
     * instancia o controlador correspondente à rota
     *
     * @method initController
     * @return void
     */
    private function initController(){
        $rota                = $this->getRoute();
        $nameSpace           = 'app\\Controller\\'.$rota;        
        new $nameSpace;
    }

}