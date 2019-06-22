<?php

  /**
   * FileCache
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
    
  class FileCache extends AbstractCache implements CacheInterface
  {
    
    /**
     * Set the options for this adapter
     * 
     * @return bool
     */
    public static function install() : bool
    {
      
      return LazyCache::setOptions([
        'fileCache' => [
          'path' => __DIR__.'/../../../../cache'
        ]
      ]);
      
    }
    
    /**
     * Create the cache folder
     */
    public function init()
    {
      @mkdir($this->path);
    }
    
    /**
     * Get the cache file
     * 
     * @param string $key
     * @return string
     */
    protected function getCacheFile(string $key) : string
    {
      return $this->path.'/'.$this->getCacheKey($key);
    }
    
    /**
     * Get the cache content
     * 
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
      
      $file = $this->getCacheFile($key);

      // if cache file exists
      if (file_exists($file)) {
        
        $time = filemtime($file);

        // if cache is valid, return it
        if ($time >= time() || is_null($time)) {
          return unserialize(file_get_contents($file));
        // otherwise delete it
        } else {
          unlink($file);
        }

      }

      return false;
      
    }

    /**
     * Set the cache content
     * 
     * @param string $key
     * @param type $data
     * @param int $timeout
     */
    public function set(string $key, $data, int $timeout = null)
    {
      
      if (is_null($timeout)) {
        $timeout = LazyCache::getInstance()->timeout;
      }
      
      if (is_null($timeout)) {
        $timeout = 60*60*24*365;
      }
      
      $timeout += time();
      
      $file = $this->getCacheFile($key);
      
      // write content the cache file and set the time
      file_put_contents($file, serialize($data));
      touch($file, $timeout, $timeout);
      
    }

    /**
     * Delete the cache content
     * 
     * @param string $key
     * @return bool
     */
    public function delete(string $key) : bool
    {
      
      $file = $this->getCacheFile($key);

      // if cache file exists, delete the file
      if (file_exists($file)) {
        return unlink($file);
      }
      
      return false;
      
    }

    /**
     * Flush the whole cache
     */
    public function flush()
    {
      
      // glob cache folder and delete each file
      foreach (glob($this->path.'/*') as $file) {
        unlink($file);
      }
      
    }
    
  }