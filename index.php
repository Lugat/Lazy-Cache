<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: http://squareflower.de
   * Description: 
   * Version: 0.0.1
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
    
    $data = LazyCache::read();
    if ($data !== false) {
      
      $html = LazyCache::evaluateFragments($data);
      
      echo $html;
      exit();
      
    }
    
    ob_start(function($html) {
      
      LazyCache::minify($html);
      
      LazyCache::write($html);
      
      return $html;

    });

  }, 0);