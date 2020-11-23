<?php

  function lazy_cache_render_dynamic(string $statement)
  {
    echo Jinx\LazyCache::getInstance()->renderDynamic($statement);
  }
  
  function lazy_cache_get_template_part($slug, $name = null)
  {
    $name = is_null($name) ? 'null' : "'$name'";
    echo Jinx\LazyCache::getInstance()->renderDynamic("get_template_part('$slug', $name);"); 
  }
  
  function lazy_cache_begin_cache(string $key, int $timeout = 0) : bool
  {
    return Jinx\LazyCache::getInstance()->beginCache($key, $timeout);
  }
  
  function lazy_cache_end_cache()
  {
    Jinx\LazyCache::getInstance()->endCache();
  }