<?php

  $options = get_option('lazy-cache');
  
?>

<div class="wrap">

  <h1>Lazy Cache</h1>
  
  <?php do_action('admin_notices'); ?>

  <form method="post" novalidate="novalidate">

    <h2 class="title"><?= __('General settings', 'lazy-cahce'); ?></h2>
    
    <table class="form-table">
      <tbody>

        <tr>
          <th scope="row">
            <label for="lazy-cache-active"><?= __('Activate', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[active]" type="hidden" value="0">
            <input name="lazy-cache[active]" type="checkbox" id="lazy-cache-active" <?= $options['active'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>

        <tr>
          <th scope="row">
            <label for="lazy-cache-timeout"><?= __('Timeout', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[timeout]" type="number" id="lazy-cache-timeout" min="0" value="<?= $options['timeout']; ?>" class="regular-text">
            <p class="description"><?= __('Number of seconds how long the cache-files are valid. Default is one day. Leave empty or 0 to store as long as possible.', 'lazy-cache'); ?></p>
          </td>
        </tr>
        
      </tbody>
    </table>
    
    <hr />
    
    <h2 class="title"><?= __('Rules', 'lazy-cahce'); ?></h2>
    
    <p><?= __('You may specify some rules to avoid caching.'); ?><p>
    
    <table class="form-table">
      <tbody>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-logged-in-users"><?= __('Ignore logged in users', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[ignore-logged-in-users]" type="hidden" value="0">
            <input name="lazy-cache[ignore-logged-in-users]" type="checkbox" id="lazy-cache-ignore-logged-in-users" <?= $options['ignore-logged-in-users'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-query-string"><?= __('Ignore query string', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[ignore-query-string]" type="hidden" value="0">
            <input name="lazy-cache[ignore-query-string]" type="checkbox" id="lazy-cache-ignore-query-string" <?= $options['ignore-query-string'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-paths"><?= __('Ignore paths', 'lazy-cache'); ?></label>
          </th>
          <td>
            <textarea name="lazy-cache[ignore-paths]" id="lazy-cache-ignore-paths" class="regular-text"><?= implode("\n", $options['ignore-paths']); ?></textarea>
            <p class="description"><?= __('One path per line. To ignore the front-page use "/".', 'lazy-cache'); ?></p>
          </td>
        </tr>

      </tbody>
    </table>
    
    <hr />
    
    <h2 class="title"><?= __('Extras', 'lazy-cahce'); ?></h2>
    
    <p><?= __('Lazy Cache offers some extra features which can be activated.'); ?><p>
    
    <table class="form-table">
      <tbody>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-minify-html"><?= __('Minify HTML', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[minify-html]" type="hidden" value="0">
            <input name="lazy-cache[minify-html]" type="checkbox" id="lazy-cache-minify-html" <?= $options['minify-html'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>
        
      </tbody>
    </table> 
    
    <hr />

    <p class="submit">
      
      <button type="submit" name="lazy-cache-action" value="save" id="submit" class="button button-primary"><?= __('Save', 'lazy-cache'); ?></button>
      
      <button type="submit" name="lazy-cache-action" value="reset" id="submit" class="button button-secondary"><?= __('Reset', 'lazy-cache'); ?></button>
            
    </p>
  
  </form>

</div>