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
    public static function run(string $action) : string
    {
      
      $controller = new self;
      $action = 'action'.ucfirst($action);
      
      // if action exists
      if (method_exists($controller, $action)) {
        return $controller->$action();
      } else {
        \wp_die(__("The action '$action' does not exist.", 'Lazy Cache'), 'Lazy Cache');
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
        \wp_die(__("The view '$view' does not exist.", 'Lazy Cache'), 'Lazy Cache');
      }
      
    }
    
    /**
     * Admin action
     */
    public function actionAdmin()
    {
      
      $options = LazyCache::getOptions();
      
      return $this->render('admin', [
        'options' => $options
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
        $options['enable'] = boolval($options['enable']);
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
    
  }