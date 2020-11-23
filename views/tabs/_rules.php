<h2 class="title"><?= __('Rules', 'lazy-cache'); ?></h2>

<p><?= __('You may specify some rules to avoid caching.', 'lazy-cache'); ?><p>

<table class="form-table">
  <tbody>

    <tr>
      <th scope="row">
        <label for="lazy-cache-ignore-logged-in-users"><?= __('Ignore logged in users', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[ignoreLoggedInUsers]" type="hidden" value="0">
          <input name="lazy-cache[ignoreLoggedInUsers]" type="checkbox" id="lazy-cache-ignore-logged-in-users" <?= $options['ignoreLoggedInSsers'] ? 'checked' : ''; ?> value="1" />
        </p>
      
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-ignore-query-string"><?= __('Ignore query string', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[ignoreQueryString]" type="hidden" value="0">
          <input name="lazy-cache[ignoreQueryString]" type="checkbox" id="lazy-cache-ignore-query-string" <?= $options['ignoreQueryString'] ? 'checked' : ''; ?> value="1" />
        </p>
        
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-ignore-query-params"><?= __('Ignore query params', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[ignoreQueryParams]" type="hidden" value="0">
          <input name="lazy-cache[ignoreQueryParams]" type="checkbox" id="lazy-cache-ignore-query-params" <?= $options['ignoreQueryString'] ? 'checked' : ''; ?> value="1" />
        </p>
        
        <p>
          <input type="text" name="lazy-cache[ignoreQueryParamsValue]" value="<?= implode(",", $options['ignoreQueryParamsValue']); ?>" class="regular-text">
        </p>
        
        <p class="description"><?= __('Separated each item by comma.', 'lazy-cache'); ?></p>
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-ignore-paths"><?= __('Ignore paths', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[ignorePaths]" type="hidden" value="0">
          <input name="lazy-cache[ignorePaths]" type="checkbox" id="lazy-cache-ignore-query-paths" <?= $options['ignorePaths'] ? 'checked' : ''; ?> value="1" />
        </p>
        
        <p>
          <textarea name="lazy-cache[ignorePathsValue]" class="regular-text"><?= implode("\n", $options['ignorePathsValue']); ?></textarea>
        </p>
        
        <p class="description"><?= __('One path per line. To ignore the front-page use "/".', 'lazy-cache'); ?></p>
        
      </td>
    </tr>

  </tbody>
</table>