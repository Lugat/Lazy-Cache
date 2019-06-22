<?php

  $options = Jinx\LazyCache::getOptions();
  
?>

<div class="wrap">

  <h1>Lazy Cache</h1>
  
  <form method="post" novalidate="novalidate">

    <h2 class="title"><?= __('General settings', 'lazy-cahce'); ?></h2>
    
    <table class="form-table">
      <tbody>

        <tr>
          <th scope="row">
            <label for="lazy-cache-enable"><?= __('Enable', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[enable]" type="hidden" value="0">
            <input name="lazy-cache[enable]" type="checkbox" id="lazy-cache-enable" <?= $options['enable'] ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
        <!-- @todo: AdapterClass selection / filters -->

        <tr>
          <th scope="row">
            <label for="lazy-cache-timeout"><?= __('Timeout', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[timeout]" type="number" id="lazy-cache-timeout" min="0" value="<?= $options['timeout']; ?>" class="regular-text">
            <p class="description"><?= __('Number of seconds how long the cache-files are valid. Default is one day. Leave empty or 0 to store as long as possible.', 'lazy-cache'); ?></p>
          </td>
        </tr>
        
        <!-- @todo: Path -->
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-file-cache-prefix"><?= __('File prefix', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[fileCache][prefix]" type="text" id="lazy-cache-file-cache-prefix" value="<?= $options['fileCache']['prefix']; ?>" class="regular-text">
            <p class="description"><?= __('The prefix for the cache files.', 'lazy-cache'); ?></p>
          </td>
        </tr>
        
      </tbody>
    </table>
    
    <hr />
    
    <h2 class="title"><?= __('Page cache', 'lazy-cache'); ?></h2>
    
    <table class="form-table">
      <tbody>

        <tr>
          <th scope="row">
            <label for="lazy-cache-enable-page-cache"><?= __('Enable page cache', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[enablePageCache]" type="hidden" value="0">
            <input name="lazy-cache[enablePageCache]" type="checkbox" id="lazy-cache-enable-page-cache" <?= $options['enablePageCache'] ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
      </tbody>
    </table>
    
    <p><?= __('You may specify some rules to avoid caching.'); ?><p>
    
    <table class="form-table">
      <tbody>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-logged-in-users"><?= __('Ignore logged in users', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[ignoreLoggedInUsers]" type="hidden" value="0">
            <input name="lazy-cache[ignoreLoggedInUsers]" type="checkbox" id="lazy-cache-ignore-logged-in-users" <?= $options['ignoreLoggedInSsers'] ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-query-string"><?= __('Ignore query string', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[ignoreQueryString]" type="hidden" value="0">
            <input name="lazy-cache[ignoreQueryString]" type="checkbox" id="lazy-cache-ignore-query-string" <?= $options['ignoreQueryString'] ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-query-params"><?= __('Ignore query params', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input type="text" name="lazy-cache[ignoreQueryParams]" id="lazy-cache-ignore-query-params" value="<?= implode(",", $options['ignoreQueryParams']); ?>" class="regular-text">
            <p class="description"><?= __('Separated each item by comma.', 'lazy-cache'); ?></p>
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-paths"><?= __('Ignore paths', 'lazy-cache'); ?></label>
          </th>
          <td>
            <textarea name="lazy-cache[ignorePaths]" id="lazy-cache-ignore-paths" class="regular-text"><?= implode("\n", $options['ignorePaths']); ?></textarea>
            <p class="description"><?= __('One path per line. To ignore the front-page use "/".', 'lazy-cache'); ?></p>
          </td>
        </tr>

      </tbody>
    </table>
    
    <hr />
    
    <h2 class="title"><?= __('Extras', 'lazy-cache'); ?></h2>
    
    <p><?= __('Lazy Cache offers some extra features which can be activated.'); ?><p>
    
    <table class="form-table">
      <tbody>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-minify-html"><?= __('Minify HTML', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[minifyHtml]" type="hidden" value="0">
            <input name="lazy-cache[minifyHtml]" type="checkbox" id="lazy-cache-minify-html" <?= $options['minifyHtml'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
      </tbody>
    </table> 
    
    <hr />

    <p class="submit">
      
      <button type="submit" name="lazy-cache-action" value="update" id="submit" class="button button-primary"><?= __('Save', 'lazy-cache'); ?></button>
      
      <button type="submit" name="lazy-cache-action" value="reset" id="submit" class="button button-secondary"><?= __('Reset', 'lazy-cache'); ?></button>
            
    </p>
  
  </form>

</div>