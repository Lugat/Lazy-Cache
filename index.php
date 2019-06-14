<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: https://wordpress.org/plugins/lazy-cache/
   * Description: Simple cache plugin which supports dynamic rendering
   * Version: 0.1.3
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   * License: GPL2+
   * Text Domain: lazy-cache
   */

  require_once('LazyCache.php');
  
  LazyCache::init();
    
  add_action('admin_menu', function() {
    
    add_submenu_page('options-general.php', 'Lazy Cache', 'Lazy Cache', 'manage_options', 'lazy-cache', function() {

      include 'admin.php';

    });

  }, 0);
  
  add_action('admin_init', function() {
    
    if (isset($_REQUEST['lazy-cache-action'])) {
      
      switch ($_REQUEST['lazy-cache-action']) {
        
        case 'save':
          
          if (isset($_POST['lazy-cache'])) {

            $options = (array) $_POST['lazy-cache'];

            $options['ignore-paths'] = explode("\n", $options['ignore-paths']);
            $options['ignore-paths'] = array_map('trim', $options['ignore-paths']);
            $options['ignore-paths'] = array_filter($options['ignore-paths']);

            update_option('lazy-cache', array_merge(get_option('lazy-cache'), $options));

            add_action('admin_notices', function() { 
              echo '<div class="notice notice-success is-dismissible"><p>'.__('The settings have been saved!', 'lazy-cache').'</p></div>';
            });

          }
          
        break;
      
        case 'reset':
          
          LazyCache::install();
          
          add_action('admin_notices', function() { 
            echo '<div class="notice notice-success is-dismissible"><p>'.__('The settings have been reset!', 'lazy-cache').'</p></div>';
          });
          
        break;
      
        case 'flush':
          
          LazyCache::flush();
          
          add_action('admin_notices', function() { 
            echo '<div class="notice notice-success is-dismissible"><p>'.__('The cache has been flushed!', 'lazy-cache').'</p></div>';
          });
          
        break;
        
      }
      
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
  
  register_activation_hook(__FILE__, ['LazyCache', 'install']);
    
  add_action('template_redirect', ['LazyCache', 'load'], 0);
  add_action('template_redirect', ['LazyCache', 'write'], 9999);
  
  function render_dynamic($statement)
  {
    echo LazyCache::renderDynamic($statement);
  }
  
  function get_template_part_dynamic($slug, $name = null)
  {
    
    $name = is_null($name) ? 'null' : "'$name'";
    
    echo LazyCache::renderDynamic("get_template_part('$slug', $name);");
    
  }