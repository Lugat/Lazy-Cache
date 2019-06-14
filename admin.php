<?php

  $options = get_option('lazy-cache');
  
?>

<div class="wrap">

  <h1>Lazy Cache</h1>
  
  <?php do_action('admin_notices'); ?>

  <form method="post" novalidate="novalidate">

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
            <label for="lazy-cache-minify-html"><?= __('Minify HTML', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[minify-html]" type="hidden" value="0">
            <input name="lazy-cache[minify-html]" type="checkbox" id="lazy-cache-minify-html" <?= $options['minify-html'] == 1 ? 'checked' : ''; ?> value="1" />
          </td>
        </tr>

        <tr>
          <th scope="row">
            <label for="lazy-cache-timeout"><?= __('Timeout', 'lazy-cache'); ?></label>
          </th>
          <td>
            <input name="lazy-cache[timeout]" type="number" id="lazy-cache-timeout" min="0" value="<?= $options['timeout']; ?>" class="regular-text">
            <p class="description"><?= __('Number of seconds how long the cache-files are valid.', 'lazy-cache'); ?></p>
          </td>
        </tr>
        
        <tr>
          <th scope="row">
            <label for="lazy-cache-ignore-paths"><?= __('Ignore paths', 'lazy-cache'); ?></label>
          </th>
          <td>
            <textarea name="lazy-cache[ignore-paths]" id="lazy-cache-ignore-paths" class="regular-text"><?= implode("\n", $options['ignore-paths']); ?></textarea>
            <p class="description"><?= __('One path per line.', 'lazy-cache'); ?></p>
          </td>
        </tr>

      </tbody>

    </table>

    <p class="submit">
      
      <button type="submit" name="lazy-cache-action" value="save" id="submit" class="button button-primary"><?= __('Save', 'lazy-cache'); ?></button>
      
      <button type="submit" name="lazy-cache-action" value="reset" id="submit" class="button button-secondary"><?= __('Reset', 'lazy-cache'); ?></button>
            
    </p>
  
  </form>

</div>