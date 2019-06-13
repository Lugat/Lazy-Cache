<?php

  class LazyCache
  {
    
    protected static $file;
    protected static $options;
    
    protected static $fragments = [];
    
    public static function init()
    {
      
      $defaultOptions = [
        'path' => __DIR__.'/../../_lazy-cache',
        'timeout' => 60*60*24,
        'gzip' => false
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
      
      self::$fragments = [];
      
      $timeout = time() + self::$options['timeout'];
      
      $data = serialize($data);
      
      if (self::$options['gzip']) {
        $data = gzcompress($data);
      }
      
      file_put_contents(self::$file, $data);
      touch(self::$file, $timeout, $timeout);
      
    }
    
    public static function read()
    {
      
      if (file_exists(self::$file)) {
        
        $time = filemtime(self::$file);

        if ($time >= time() || is_null($time)) {
          
          $data = file_get_contents(self::$file);
          
          if (self::$options['gzip']) {
            $data = gzuncompress($data);
          }
          
          return unserialize($data);
          
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
    
    public static function evaluateFragments(array $data)
    {
      
      return preg_replace_callback('/<!\[CDATA\[LAZY-CACHE-FRAGMENT-(\d+)\]\]>/', function($matches) use($data) {
        
        list(, $n) = $matches;
        
        if (isset($data['fragments'][$n])) {
          
          ob_start();
          ob_implicit_flush(false);

          eval($data['fragments'][$n]);  

          return ob_get_clean();
          
        }
        
        return null;
        
      }, $data['html']);
      
    }
    
    public static function minify(&$html)
    {
      
      $search = [
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/' // Remove HTML comments
      ];

      $replace = [
        '>',
        '<',
        '\\1',
        ''
      ];

      $html = trim(preg_replace($search, $replace, $html));

    }
    
  }