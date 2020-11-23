<?php

  /**
   * DbCache
   * 
   * @package Jinx\LazyCache
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.1.0
   * @since 0.3.0
   */

  namespace Jinx\LazyCache;
  
  use Jinx\LazyCache;
    
  class DbCache extends AbstractCache implements CacheInterface
  {
    
    protected $wpdb;
    
    /**
     * Set the options for this adapter
     * 
     * @return bool
     */
    public static function install() : bool
    {
            
      return LazyCache::setOptions([
        'dbCache' => [
          'tableName' => 'lazy-cache'
        ]
      ]);
      
    }
    
    /**
     * Set wbdb
     */
    public function init()
    {
      
      global $wpdb;
      
      $this->wbdb = $wpdb;
      
    }
    
    /**
     * Get the cache content
     * 
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
            
      $query = "SELECT `data`, `timeout` FROM $this->tableName WHERE `key` = $key LIMIT 0, 1";
      
      $row = $this->wpdb->get_row($query);
      
      if (isset($row)) {
        
        if ($row->timeout >= time()) {
          return unserialize($row->data);
        } else {
          $this->delete($key);
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
    public function set(string $key, $data, int $timeout = 0)
    {
      
      if (empty($timeout)) {
        $timeout = LazyCache::getInstance()->timeout;
      }
      
      if (is_null($timeout)) {
        $timeout = 60*60*24*365;
      }
      
      $timeout += time();
            
      $query = "INSERT INTO $this->tableName (`key`, `data`, `timeout`) VALUES ($key, ".serialize($data).", $timeout)";
      
      $this->wbdb->query($query);
      
    }

    /**
     * Delete the cache content
     * 
     * @param string $key
     * @return bool
     */
    public function delete(string $key) : bool
    {
      
      $query = "DELETE FROM $this->tableName WHERE `key` = $key";
      
      return $this->wbdb->query($query);
            
    }

    /**
     * Flush the whole cache
     */
    public function flush()
    {
      
      $query = "DELETE FROM $this->tableName";
      
      $this->wbdb->query($query);
      
    }
    
  }