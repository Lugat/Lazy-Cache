<?php
  
  /**
   * Plugin Name: Lazy Cache
   * Plugin URI: http://squareflower.de
   * Description: 
   * Version: 0.1.0
   * Author: SquareFlower Websolutions (Lukas Rydygel) <hallo@squareflower.de>
   * Author URI: http://squareflower.de
   * Text Domain: lazy-cache
   */

  require_once('LazyCache.php');
  
  LazyCache::init();
    
  add_action('admin_menu', function() {
    
    add_submenu_page('options-general.php', 'Lazy Cache', 'Lazy Cache', 'manage_options', 'lazy-cache', function() {
      
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

      include 'admin.php';

    });

  }, 0);
  
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