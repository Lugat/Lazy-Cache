<?php

  /**
   * Configurable
   * 
   * @package Jinx\LazyCache
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1.0
   * @since 0.2.0
   */

  namespace Jinx\LazyCache;
  
  abstract class Configurable
  {
    
    protected $config = [];
    
    /**
     * Construct with a config and call init
     * 
     * @param array $config
     */
    protected function __construct(array $config = [])
    {
      
      $this->config = $config;
      
      $this->init();
      
    }
    
    /**
     * Modify the element after is has been instanciated
     */
    protected function init()
    {
    }
    
    /**
     * Get object property or configuration value
     * 
     * @param type $name
     * @return type
     */
    public function __get($name)
    {
      
      // if property exists and isset
      if (property_exists($this, $name) && isset($this->$name)) {
        return $this->$name;
      // is confif exists and isset
      } elseif (array_key_exists($name, $this->config) && isset($this->config[$name])) {
        return $this->config[$name];
      }
      
      return null;
      
    }
    
  }