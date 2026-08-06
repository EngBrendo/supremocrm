<?php

namespace src\traits;

/**
 * Trait responsável pelos métodos mágicos get e set
 */
trait GetSet{

  /**
   * Método responsável pelos GETs das tabelas
   * @method __get
   * @param  string $property Propriedade da tabela
   * @return valor da propriedade
   */
  function __get($property){
    return $this->$property;
  }

  /**
   * Método responsável pelos SETs das tabelas
   * @method __set
   * @param  string $property nome da propriedade
   * @param  string $value       valor
   */
  function __set($property,$value){
    $property = $this->getPropertyClass($property);
    $this->$property = $value;
  }

  /**
   * Método responsável por realizar o de/para das propriedades das classes e colunas do DB
   * @method getPropertyClass
   * @param  string           $property Coluna do DB
   * @return string           propriedade da classe
   */
  public function getPropertyClass($property){
    $properties = method_exists($this,'getProperties') ? array_flip($this->getProperties()) : array();
    $property = isset($properties[$property])?$properties[$property]:$property;
    return $property;
  }

}
