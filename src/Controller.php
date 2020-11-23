<?php

  /**
   * Controller
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
  use Jinx\LazyCache\Helper;
  
  class Controller
  {
    
    protected $viewPath = __DIR__.'/../views';
    
    /**
     * Run a given action
     * 
     * @param string $action
     */
    public static function run(string $action)
    {
      
      $controller = new self;
      $action = 'action'.ucfirst($action);
      
      // if action exists
      if (method_exists($controller, $action)) {
        return $controller->$action();
      } else {
        \wp_die(sprintf(__("The action '%s' does not exist.", 'Lazy Cache'), 'Lazy Cache'), $action);
      }
      
    }
    
    public function render(string $view, array $params = []) : string
    {
      
      $file = $this->viewPath.'/'.$view.'.php';
      
      if (file_exists($file)) {
                
        return Helper::captureOutput(function() {

          extract(func_get_arg(1));
          include func_get_arg(0);

        }, $file, $params);
        
      } else {
        \wp_die(sprintf(__("The view '%s' does not exist.", 'Lazy Cache'), 'Lazy Cache'), $view);
      }
      
    }
    
    /**
     * Admin action
     */
    public function actionAdmin()
    {
            
      $options = LazyCache::getOptions();
      
      $adapters = apply_filters(LazyCache::FILTER_ADAPTERS, []);
      
      $postTypes = get_post_types([
        'publicly_queryable' => true
      ], 'object');
                       
      return $this->render('admin', [
        'options' => $options,
        'adapters' => $adapters,
        'postTypes' => $postTypes
      ]);
      
    }
    
    /**
     * Update action
     */
    public function actionUpdate()
    {
      
      // if POST has been send
      if (isset($_POST['lazy-cache'])) {
        
        $options = (array) $_POST['lazy-cache'];
        
        // sanitize and validate
        $options['adapter'] = sanitize_text_field($options['adapter']);
        $options['timeout'] = intval($options['timeout']);
        $options['enablePageCache'] = boolval($options['enablePageCache']);
        $options['ignoreLoggedInUsers'] = boolval($options['ignoreLoggedInUsers']);
        $options['ignoreQueryString'] = boolval($options['ignoreQueryString']);
        $options['minifyHtml'] = boolval($options['minifyHtml']);
        $options['ignoreQueryParams'] = Helper::explode($options['ignoreQueryParams'], ',');
        $options['ignorePaths'] = Helper::explode($options['ignorePaths'], "\n");
        
        // set options
        LazyCache::setOptions($options);
        
        add_action('admin_notices', function() { 
          echo '<div class="notice notice-success is-dismissible"><p>'.__('The settings have been saved!', 'lazy-cache').'</p></div>';
        });
        
      }
            
    }
    
    /**
     * Reset action
     */
    public function actionReset()
    {
      
      LazyCache::install();
            
      add_action('admin_notices', function() { 
        echo '<div class="notice notice-success is-dismissible"><p>'.__('The settings have been reset!', 'lazy-cache').'</p></div>';
      });
            
    }
    
    /**
     * Flush action
     */
    public function actionFush()
    {
      
      LazyCache::getInstance()->flush();

      add_action('admin_notices', function() { 
        echo '<div class="notice notice-success is-dismissible"><p>'.__('The cache has been flushed!', 'lazy-cache').'</p></div>';
      });
            
    }
    
    /**
     * Heatup action
     */
    public function actionHeatup()
    {
      
      $postTypes = get_post_types([
        'publicly_queryable' => true
      ]);
      
      $posts = get_posts([
        'post_type' => $postTypes,
        'posts_per_page' => -1
      ]);
      
      foreach ($posts as $post) {
        $this->ping(get_permalink($post));
      }
            
    }
    
    protected function ping($url)
    {
      
      $curl = curl_init();
      
      curl_setopt($curl, CURLOPT_URL, $url);

      curl_setopt($curl, CURLOPT_TIMEOUT, 1);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
      curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
      curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, true);
      curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);

      curl_exec($curl);   

      curl_close($curl); 
      
    }
    
  }