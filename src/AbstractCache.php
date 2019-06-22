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
  
  use Jinx\LazyCache;
  
  abstract class AbstractCache extends Configurable
  {
    
    /**
     * Set the options for this adapter
     * 
     * @return bool
     */
    public function install() : bool
    {
      return true;
    }
    
    /**
     * Get the cache key
     * 
     * @param type $key
     * @return type
     */
    protected function getCacheKey($key)
    {
      return LazyCache::getInstance()->prefix.md5($key);
    }
    
  }