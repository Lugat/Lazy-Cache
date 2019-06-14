<?php

  abstract class LazyCache
  {
    
    protected static $path;
    protected static $file;
    protected static $options;
    
    protected static $fragments = [];
    
    public static function install()
    {
      
      update_option('lazy-cache', [
        // fix
        'path' => __DIR__.'/../../_lazy-cache',
        // general
        'active' => 0,
        'timeout' => 86400, // 1 day
        // rules
        'ignore-logged-in-users' => 0,
        'ignore-query-string' => 0,
        'ignore-paths' => [],
        // extras
        'minify-html' => 0,
      ]);
      
    }
    
    public static function init()
    {
            
      self::$options = get_option('lazy-cache');
      
      $basePath = self::getOption('path');
      
      @mkdir($basePath);
      
      $script = addcslashes(dirname($_SERVER['SCRIPT_NAME']), '/');
      
      $path = preg_replace('/^'.$script.'/is', '', $_SERVER['REQUEST_URI']);
      
      if (self::isChecked('ignore-query-string')) {
        $path = parse_url($path, PHP_URL_PATH);
      }
      
      self::$path = $path;
      
      self::$file = $basePath.'/'.md5(self::$path);
      
    }
    
    public static function getFragments()
    {
      return self::$fragments;
    }
    
    public static function isChecked($key)
    {
      
      $value = self::getOption($key, 0);
      
      return (bool) intval($value) === true;
      
    }
    
    public static function getOption($key, $alt = null)
    {
      return isset(self::$options[$key]) ? self::$options[$key] : $alt;
    }
    
    public static function load()
    {
      
      if (self::useCache()) {
        
        if (file_exists(self::$file)) {
          
          $time = filemtime(self::$file);

          if ($time >= time() || is_null($time)) {
            
            $data = unserialize(file_get_contents(self::$file));
                        
            echo self::evaluateFragments($data['html'], $data['fragments']);
            exit();
            
          } else {
            unlink(self::$file);
          }

        }
        
      }
      
    }
    
    public static function write()
    {
      
      if (self::useCache()) {
                      
        ob_start(function($html) {
          
          if (self::isChecked('minify-html')) {
            self::minify($html);
          }
                    
          $data = [
            'html' => $html,
            'fragments' => self::$fragments
          ];
          
          $timeout = intval(self::getOption('timeout', 0));
          
          if ($timeout === 0) {
            $timeout = 31536000; // 1 year
          }     
                  
          $timeout += time();

          file_put_contents(self::$file, serialize($data));
          touch(self::$file, $timeout, $timeout);

          header('Location: '.$_SERVER['REQUEST_URI']);
          exit();

        });
                
      }
         
    }
    
    public static function flush()
    {
      
      $basePath = self::getOption('path');
      
      foreach (glob($basePath.'/*') as $file) {
        unlink($file);
      }
      
    }
    
    public static function renderDynamic($statement)
    {
            
      $n = count(self::$fragments);
      
      self::$fragments[] = $statement;

      return "<![CDATA[LAZY-CACHE-$n]]>";
      
    }
    
    public static function evaluateFragments($html, array $fragments)
    {
                  
      return preg_replace_callback('/<!\[CDATA\[LAZY-CACHE-(\d+)\]\]>/', function($matches) use($fragments) {
        
        list(, $n) = $matches;
        
        if (isset($fragments[$n])) {
          
          ob_start();
          ob_implicit_flush(false);

          eval($fragments[$n]);

          return ob_get_clean();
          
        }
        
        return null;
        
      }, $html);
      
    }
    
    protected static function useCache()
    {
            
      if (self::isChecked('active')) {
        
        if (self::isChecked('ignore-logged-in-users') && is_user_logged_in()) {
          return false;
        }
        
        if (get_post_status() !== 'publish') {
          return false;
        }
        
        $paths = self::getOption('ignore-paths');
        if (!empty($paths)) {
                  
          if (preg_match('/('.addcslashes(implode('|', $paths), '/').')/i', self::$path)) {
            return false;
          }
        
        }
        
        return true;
        
      }
      
      return false;
            
    }
    
    protected static function minify(&$html)
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