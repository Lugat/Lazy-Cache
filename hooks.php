<?php

  add_filter(Jinx\LazyCache::FILTER_ADAPTERS, function($adapters) {
          
    return array_merge($adapters, [
      'Jinx\LazyCache\DummyCache' => 'Dummy Cache',
      'Jinx\LazyCache\FileCache' => 'File Cache',
      'Jinx\LazyCache\DbCache' => 'Database Cache'
    ]);
       
  });

  add_filter(Jinx\LazyCache::FILTER_USE_CACHE, function($useCache) {
          
    if (Jinx\LazyCache::getInstance()->ignoreLoggedInUsers && is_user_logged_in()) {
      return false;
    }
    
    return $useCache;
   
  });
  
  add_filter(Jinx\LazyCache::FILTER_USE_PAGE_CACHE, function($usePageCache) {
    
    if (get_post_status() !== 'publish') {
      return false;
    }
    
    return $usePageCache;
    
  });
  
  add_filter(Jinx\LazyCache::FILTER_USE_PAGE_CACHE, function($usePageCache) {
    
    $instance = Jinx\LazyCache::getInstance();
    
    if ($instance->enablePageCache) {
        
      // if ignore paths are defined
      if ($instance->ignorePaths) {

        // if request URI matches one of the ignore paths
        if (preg_match('/('.addcslashes(implode('|', $instance->ignorePathsValues), '/').')/i', $instance->path)) {
          return false;
        }

      }

      return $usePageCache;

    }

    return false;
    
  });