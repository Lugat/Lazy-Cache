<?php
  
  /**
   * CacheInterface
   * 
   * @package Jinx\LazyCache
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1.0
   * @since 0.2.0
   */

  namespace Jinx\LazyCache;

  interface CacheInterface
  {
    
    /**
     * Get the cache content
     * 
     * @param string $key
     */
    public function get(string $key);
    
    /**
     * Set the cache content
     * 
     * @param string $key
     * @param type $value
     * @param int $timeout
     */
    public function set(string $key, $value, int $timeout);
    
    /**
     * Delete the cache content
     * 
     * @param string $key
     */
    public function delete(string $key);
    
    /**
     * Flush the whole cache
     */
    public function flush();
    
  }