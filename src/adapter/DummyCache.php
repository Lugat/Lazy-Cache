<?php
  
  /**
   * DummyCache
   * 
   * @package Jinx\LazyCache
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1.0
   * @since 0.3.0
   */
  
  namespace Jinx\LazyCache;

  class DummyCache extends AbstractCache implements CacheInterface
  {
    
    public function get(string $key)
    {
      return false;
    }

    public function set(string $key, $data, int $timeout = 0) {}

    public function delete(string $key) {}

    public function flush() {}  
    
  }