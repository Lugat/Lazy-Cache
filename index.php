<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: http://squareflower.de
   * Description: 
   * Version: 0.0.2
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   * Text Domain: lazy-cache
   */

  require_once('LazyCache.php');
  
  LazyCache::init();
  
  function lazy_cache_render_dynamic($statement)
  {
    echo LazyCache::renderDynamic($statement);
  }

  add_action('template_redirect', function() {
    
    $html = LazyCache::load();
    if ($html !== false) {
            
      echo LazyCache::evaluateFragments($html);
      exit();
      
    }

  }, 0);
  
  add_action('template_redirect', function() {
    
    ob_start(function($html) {

      LazyCache::write($html);  

      header('Location: '.$_SERVER['REQUEST_URI']);
      exit();

    });

  }, 9999);