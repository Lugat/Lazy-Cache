<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: https://wordpress.org/plugins/lazy-cache/
   * Description: Simple cache plugin which supports dynamic rendering
   * Version: 0.2.0
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   * License: GPL2+
   * Text Domain: lazy-cache
   */

  require_once 'src/Helper.php';
  require_once 'src/Configurable.php';
  require_once 'src/LazyCache.php';
  require_once 'src/Controller.php';
  require_once 'src/CacheInterface.php';
  require_once 'src/AbstractCache.php';
  
  add_action('admin_menu', function() {
    
    add_submenu_page('options-general.php', 'Lazy Cache', 'Lazy Cache', 'manage_options', 'lazy-cache', function() {
      echo \Jinx\LazyCache\Controller::run('admin'); 
    });

  });
  
  add_action('admin_init', function() {
    
    if (isset($_REQUEST['lazy-cache-action'])) {      
      \Jinx\LazyCache\Controller::run($_REQUEST['lazy-cache-action']);
    }
    
  });

  add_action('admin_bar_menu', function($adminBar) {
    
    if (is_admin()) {

      $adminBar->add_node([
        'id' => 'lazy-cache-flush',
        'title' => '<span class="ab-icon dashicons dashicons-trash"></span> Lazy Cache Flush',
        'href' => '?lazy-cache-action=flush'
      ]);
    
    }

  }, 999);
    
  register_activation_hook(__FILE__, ['\Jinx\LazyCache', 'install']);
   
  add_action('template_redirect', ['\Jinx\LazyCache', 'loadPageCache'], 0);
  add_action('template_redirect', ['\Jinx\LazyCache', 'writePageCache'], 9999);
  
  function lazy_cache_render_dynamic(string $statement)
  {
    echo \Jinx\LazyCache::getInstance()->renderDynamic($statement);
  }
  
  function lazy_cache_get_template_part($slug, $name = null)
  {
    $name = is_null($name) ? 'null' : "'$name'";
    echo \Jinx\LazyCache::getInstance()->renderDynamic("get_template_part('$slug', $name);"); 
  }
  
  function lazy_cache_begin_cache(string $key, int $timeout = 0) : bool
  {
    return \Jinx\LazyCache::getInstance()->beginCache($key, $timeout);
  }
  
  function lazy_cache_end_cache()
  {
    \Jinx\LazyCache::getInstance()->endCache();
  }