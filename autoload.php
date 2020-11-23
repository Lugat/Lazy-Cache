<?php

  add_filter('lazy_cache_autoload', function($classMap) {
    
    return array_merge($classMap, [
      'Jinx\LazyCache' => __DIR__.'/src/LazyCache.php',
      'Jinx\LazyCache\Helper' => __DIR__.'/src/Helper.php',
      'Jinx\LazyCache\Configurable' => __DIR__.'/src/Configurable.php',
      'Jinx\LazyCache\Controller' => __DIR__.'/src/Controller.php',
      'Jinx\LazyCache\CacheInterface' => __DIR__.'/src/CacheInterface.php',
      'Jinx\LazyCache\AbstractCache' => __DIR__.'/src/AbstractCache.php',
      'Jinx\LazyCache\DummyCache' => __DIR__.'/src/adapter/DummyCache.php',
      'Jinx\LazyCache\FileCache' => __DIR__.'/src/adapter/FileCache.php',
      'Jinx\LazyCache\DbCache' => __DIR__.'/src/adapter/DbCache.php',
    ]);
    
  }, 0);

  spl_autoload_register(function($class) {

    $classMap = apply_filters('lazy_cache_autoload', []);
    
    if (array_key_exists($class, $classMap) && file_exists($classMap[$class])) {
      require_once($classMap[$class]);
    }

  });
