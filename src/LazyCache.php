<?php

  /**
   * LazyCache
   * 
   * @package Jinx
   * @copyright Copyright (c) 2019 SquareFlower Websolutions
   * @license GPL2+
   * @author Lukas Rydygel <hallo@squareflower.de>
   * @version 0.2.0
   * @since 0.2.0
   */

  namespace Jinx;
  
  use Jinx\LazyCache\Helper;
  use Jinx\LazyCache\Configurable;

  class LazyCache extends Configurable
  {
    
    const OPTIONS_KEY = 'lazy-cache';
    
    protected static $instance;
    
    protected $request;
    protected $fragments = [];
    protected $adapter;
    protected $key;
    protected $timeout;
    
    protected $caching = false;
    
    /**
     * Set the original plugin options
     */
    public static function install()
    {
      
      self::setOptions([
        // general
        'enable' => false,
        'timeout' => 86400, // 1 day
        'prefix' => 'lc_', 
        'adapterClass' => '\Jinx\LazyCache\FileCache',
        'enablePageCache' => false,
        'ignoreLoggedInUsers' => false,
        'ignoreQueryString' => false,
        'ignoreQueryParams' => [],
        'ignorePaths' => [],
        'minifyHtml' => false,
      ]);
      
      self::installAdapter();
      
    }    
    
    /**
     * Set and overwrite the plugins options
     * 
     * @param array $options
     * @return bool
     */
    public static function setOptions(array $options = []) : bool
    { 
      return update_option(self::OPTIONS_KEY, array_replace_recursive(self::getOptions(), $options));       
    }
    
    /**
     * Get the plugins options
     * 
     * @return array
     */
    public static function getOptions() : array
    {
      return get_option(self::OPTIONS_KEY, []);
    }
    
    /**
     * Install the active adapter
     */
    public static function installAdapter()
    {
      
      $adapterClass = self::getInstance()->loadAdapter();
      
      $adapterClass::install();
      
    }
    
    /**
     * Load the active adapter
     * 
     * @return string
     */
    protected function loadAdapter() : string
    {
      
      $adapterClass = $this->adapterClass;
      $basename = basename($adapterClass);
      
      // require if the adapter is build in
      $adapterFile = __DIR__."/adapter/$basename.php";
      if (file_exists($adapterFile)) {
        require_once $adapterFile;
      } else {
        \wp_die(__("The adapter '$basename' does not exist.", 'Lazy Cache'), 'Lazy Cache');
      }
      
      return $adapterClass;
      
    }

    /**
     * Init
     * 
     * @see \Jinx\LazyCache\Configurable
     */
    public function init()
    {
      
      $this->request = $this->getRequest();
      
      // load the active adapter
      $adapterClass = $this->loadAdapter();
      
      // instanciate the active adapter with its confug
      $adapterConfigKey = lcfirst(basename($adapterClass));
      $this->adapter = new $adapterClass($this->$adapterConfigKey);
      
    }
    
    /**
     * Get instance
     * 
     * @return \self
     */
    public static function getInstance() : self
    {
      
      if (!isset(self::$instance)) {
        self::$instance = new self(self::getOptions());
      }
      
      return self::$instance;
      
    }
    
    /**
     * Get the request URI
     * @return string
     */
    protected function getRequest() : string
    {
      
      $script = addcslashes(dirname($_SERVER['SCRIPT_NAME']), '/');
      
      $request = preg_replace('/^'.$script.'/is', '', $_SERVER['REQUEST_URI']);
      
      // remove the query string from the requested URI if option is checked
      if ($this->ignoreQueryString) {
        $request = parse_url($request, PHP_URL_PATH);
      }
      
      // @todo remove query params from the requested URI if option is defined
      
      return $request;
      
    }
    
    /**
     * Check if the cache should be used
     * 
     * @return bool
     */
    protected function useCache() : bool
    {
      
      // if cache is enabled
      if ($this->enable) {
        
        // if logged in user should be ignored and user is logged in
        if ($this->ignoreLoggedInUsers && is_user_logged_in()) {
          return false;
        }
        
        // only cache published content
        if (get_post_status() !== 'publish') {
          return false;
        }
        
        return true;
        
      }
      
      return false;
      
    }
    
    /**
     * Check if the page cache should be used
     * 
     * @return bool
     */
    protected function usePageCache() : bool
    {
      
      // if cache should be used and page cache is enabled
      if ($this->useCache() && $this->enablePageCache) {
        
        // if ignore paths are defined
        if (!empty($this->ignorePaths)) {
          
          // if request URI matches one of the ignore paths
          if (preg_match('/('.addcslashes(implode('|', $this->ignorePaths), '/').')/i', self::$path)) {
            return false;
          }
        
        }
        
        // set caching to active
        $this->caching = true;
        
        return true;
        
      }
      
      return false;
      
    }
    
    /**
     * Load data for the page cache
     */
    public static function loadPageCache()
    {
      
      $instance = self::getInstance();
      
      if ($instance->usePageCache()) {
        
        // if content is cached
        $data = $instance->adapter->get($instance->request);
        if ($data !== false) {
          
          // evaluate the fragments in the content and echo it
          echo $instance->evaluateFragments($data['html'], $data['fragments']);
          exit();
          
        }
        
      }
      
    }
    
    /**
     * Write data for the page cache
     */
    public static function writePageCache()
    {
      
      $instance = self::getInstance();
      
      // if caching is active
      if ($instance->caching) {
        
        // get the rendered HTML content
        ob_start(function($html) use($instance) {

          // if HTML should be minified
          if ($instance->minifyHtml) {
            $html = Helper::minifyHtml($html);
          }

          // save the content and fragments to the cache
          $instance->adapter->set($instance->request, [
            'html' => $html,
            'fragments' => $instance->fragments
          ]);

          // reload the page, so fragments will be rendered
          // @todo check if this can be avoided
          header('Location: '.$_SERVER['REQUEST_URI']);
          exit();

        });
      
      }
         
    }
    
    /**
     * Render dynamic content
     * 
     * @param string $statement
     * @return string
     */
    public function renderDynamic(string $statement) : string
    {
      
      // if caching is active
      if ($this->caching) {

        // get current number of fragments
        $n = count($this->fragments);
        $this->fragments[] = $statement;

        // return placeholder
        return "<![CDATA[LAZY-CACHE-$n]]>";
      
      }
      
      // evaluate statement if caching is not active
      return $this->evaluateStatment($statement);
      
    }
    
    /**
     * Evaluate fragments in HTML content
     * 
     * @param string $html
     * @param array $fragments
     * @return string
     */
    public function evaluateFragments(string $html, array $fragments) : string
    {
      
      // search and replace the fragment placeholders
      return preg_replace_callback('/<!\[CDATA\[LAZY-CACHE-(\d+)\]\]>/', function($matches) use($fragments) {
        
        list(, $n) = $matches;
        
        // evaluate the statement of the fragment
        if (isset($fragments[$n])) {
          return $this->evaluateStatment($fragments[$n]);
        }
        
        // return empty string
        return '';
        
      }, $html);
      
    }
    
    /**
     * Evaluate statement
     * 
     * @param string $statement
     * @return string
     */
    protected function evaluateStatment(string $statement) : string
    {
      
      ob_start();
      ob_implicit_flush(false);

      @eval($statement);

      return ob_get_clean();
      
    }
    
    /**
     * Begine the cache
     * 
     * @param string $key
     * @param int $timeout
     * @return bool
     */
    public function beginCache(string $key, int $timeout = 0) : bool
    {
      
      if ($this->useCache()) {
        
        // if content is cached, echo it and return false
        $data = $this->adapter->get($key);
        if ($data !== false) {
                    
          echo $data;
          return false;
          
        }
        
        // otherwise set the key and timeout for the endCache
        $this->key = $key;
        $this->timeout = $timeout;
        
        // start output buffer
        ob_start();
        ob_implicit_flush(false);
          
      }
      
      // return true, means that the content inside the if-statement should be cached until endCache
      return true;
      
    }
    
    /**
     * End the cache
     */
    public function endCache()
    {
      
      if ($this->useCache()) {
        
        // is key and timeout has been set by beginCache
        if (isset($this->key, $this->timeout)) {
      
          // get content
          $html = ob_get_clean();

          // if HTML should be minified
          if ($this->minifyHtml) {
            $html = Helper::minifyHtml($html);
          }

          // save the content to the cache
          $this->adapter->set($this->request, $html, $this->timeout);

          unset($this->key);
          unset($this->timeout);
        
        }
        
        echo $html;
      
      }
      
    }
    
  }