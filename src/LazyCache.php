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
    const DYNAMIC_PLACEHOLDER_PREFIX = 'LAZY-CACHE-DYNAMIC-';
    
    const FILTER_AUTOLOAD = 'lazy_cache_autoload';
    const FILTER_ADAPTERS = 'lazy_cache_adapters';
    const FILTER_USE_CACHE = 'lazy_cache_use_cache';
    const FILTER_USE_PAGE_CACHE = 'lazy_cache_use_page_cache';
    
    protected static $instance;
    
    protected $request;
    protected $dynamicPlaceholders = [];
    protected $adapter;
    protected $key;
    protected $timeout;
    
    public $caching = false;
    
    /**
     * Set the original plugin options
     */
    public static function install()
    {
      
      self::setOptions([
        // general
        'adapterClass' => 'Jinx\LazyCache\DummyCache',
        'timeout' => 86400, // 1 day
        'prefix' => 'lc_', 
        // page ache
        'enablePageCache' => false,
        'postTypes' => ['page', 'post'],
        // rules
        'ignoreLoggedInUsers' => false,
        'ignoreQueryString' => false,
        'ignoreQueryParams' => false,
        'ignoreQueryParamsValues' => [],
        'ignorePaths' => false,
        'ignorePathsValues' => [],
        // extras
        'minifyHtml' => false,
      ]);
      
      self::installAdapter();
      
      return true;
      
    }    
    
    /*public static function schedule()
    {
      
      // @todo change recurrence
      if (!wp_next_scheduled('lazy-cache-flush')) {
        
        wp_schedule_event(time(), 'hourly', function() {
          
          LazyCache::getInstance()->adapter->flush();
                    
        });
        
      }

    }*/
    
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
      self::getInstance()->adapterClass::install();
    }

    /**
     * Init
     * 
     * @see Jinx\LazyCache\Configurable
     */
    public function init()
    {
      
      $this->request = $this->getRequest();
      
      $adapterKey = $this->adapterClass::key();
      
      $adapterConfig = isset($this->$adapterKey) ? $this->$adapterKey : [];
      
      $this->adapter = new $this->adapterClass($adapterConfig);
      
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
     * Load data for the page cache
     */
    public static function loadPageCache()
    {
      
      if (apply_filters(self::FILTER_USE_CACHE, true) && apply_filters(self::FILTER_USE_PAGE_CACHE, true)) {
        
        $instance = self::getInstance();
        
        $instance->caching = true;
        
        // if content is cached
        $data = $instance->adapter->get($instance->request);
        if ($data !== false) {
          
          // evaluate the dynamic placebolder in the content and echo it
          echo $instance->evaluateStatements($data['html'], $data['dynamicPlaceholders']);
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

          // save the content and dynamic placeholders to the cache
          $instance->adapter->set($instance->request, [
            'html' => $html,
            'dynamicPlaceholders' => $instance->dynamicPlaceholders
          ]);

          // reload the page, so dynamic placeholders will be rendered
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

        // get current number of dynamic placeholders
        $n = count($this->dynamicPlaceholders);
        $this->dynamicPlaceholders[] = $statement;

        // return placeholder
        return '<![CDATA['.self::DYNAMIC_PLACEHOLDER_PREFIX.$n.']]>';
      
      }
      
      // evaluate statement if caching is not active
      return $this->evaluateStatment($statement);
      
    }
    
    /**
     * Evaluate statements from dynamic placeholders in HTML content
     * 
     * @param string $html
     * @param array $statements
     * @return string
     */
    public function evaluateStatements(string $html, array $statements) : string
    {
      
      // search and replace the fragment placeholders
      return preg_replace_callback('/<!\[CDATA\['.self::DYNAMIC_PLACEHOLDER_PREFIX.'(\d+)\]\]>/', function($matches) use($statements) {
        
        list(, $n) = $matches;
        
        // evaluate the statement of the fragment
        if (isset($statements[$n])) {
          return $this->evaluateStatment($statements[$n]);
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
      
      if (apply_filters(self::FILTER_USE_CACHE, true)) {
        
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
        
        echo $html;

      }
      
    }
    
  }