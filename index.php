<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: https://wordpress.org/plugins/lazy-cache/
   * Description: Simple cache plugin which supports dynamic rendering
   * Version: 0.3.0
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   * License: GPL2+
   * Text Domain: lazy-cache
   */

  require_once 'autoload.php';
  require_once 'hooks.php';
  require_once 'functions.php';
  
  add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('lazy-cache-js', plugins_url('assets/js/lazy-cache.js', __FILE__ ), ['jquery']);
  });
  
  add_action('admin_menu', function() {
    
    add_submenu_page('options-general.php', 'Lazy Cache', 'Lazy Cache', 'manage_options', 'lazy-cache', function() {
      echo Jinx\LazyCache\Controller::run('admin'); 
    });

  });
  
  add_action('admin_init', function() {
    
    if (isset($_REQUEST['lazy-cache-action'])) {
      Jinx\LazyCache\Controller::run($_REQUEST['lazy-cache-action']);
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
    
  register_activation_hook(__FILE__, ['Jinx\LazyCache', 'install']);
  //register_activation_hook(__FILE__, ['Jinx\LazyCache', 'schedule']);
   
  add_action('template_redirect', ['Jinx\LazyCache', 'loadPageCache'], 0);
  add_action('template_redirect', ['Jinx\LazyCache', 'writePageCache'], 9999);