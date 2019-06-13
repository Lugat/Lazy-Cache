<?php

  class LazyCache
  {
    
    protected static $file;
    protected static $options;
    
    public static $fragments = [];
    
    public static function init()
    {
      
      $defaultOptions = [
        'path' => __DIR__.'/../../_lazy-cache',
        'timeout' => 60*60*24
      ];
            
      self::$options = array_merge($defaultOptions, get_option('lazy-cache', []));
      
      @mkdir(self::$options['path']);
      
      self::$file = self::$options['path'].'/'.md5($_SERVER['REQUEST_URI']);
      
    }
    
    public static function write($html)
    {
      
      $data = [
        'html' => $html,
        'fragments' => self::$fragments
      ];
      
      $timeout = time() + self::$options['timeout'];
            
      file_put_contents(self::$file, serialize($data));
      touch(self::$file, $timeout, $timeout);
            
    }
    
    public static function load()
    {
      
      if (file_exists(self::$file)) {
        
        $time = filemtime(self::$file);

        if ($time >= time() || is_null($time)) {
          
          $data = unserialize(file_get_contents(self::$file));
          
          self::$fragments = $data['fragments'];
          
          return $data['html'];
          
        } else {
          unlink(self::$file);
        }

      }

      return false;
      
    }
    
    public static function renderDynamic($statement)
    {
            
      $n = count(self::$fragments);
      
      self::$fragments[] = $statement;

      return "<![CDATA[LAZY-CACHE-FRAGMENT-$n]]>";
      
    }
    
    public static function evaluateFragments($html)
    {
      
      $fragments = self::$fragments;
            
      return preg_replace_callback('/<!\[CDATA\[LAZY-CACHE-FRAGMENT-(\d+)\]\]>/', function($matches) use($fragments) {
        
        list(, $n) = $matches;
        
        if (isset($fragments[$n])) {
          
          ob_start();

          eval($fragments[$n]);

          return ob_get_clean();
          
        }
        
        return null;
        
      }, $html);
      
    }
    
  }