<h2 class="title"><?= __('General settings', 'lazy-cache'); ?></h2>

<table class="form-table">
  <tbody>

    <tr>
      <th scope="row">
        <label for="lazy-cache-adapter"><?= __('Adapter', 'lazy-cache'); ?></label>
      </th>
      <td>

        <p>
          <select name="lazy-cache[adapter]">
            <?php foreach ($adapters as $adapter => $label) : ?>
            <option value="<?= $adapter; ?>" <?= $adapter === $options['adapter'] ? 'selected' : ''; ?>><?= $label; ?></option>
            <?php endforeach; ?>
          </select>
        </p>

      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-timeout"><?= __('Timeout', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[timeout]" type="number" id="lazy-cache-timeout" min="0" value="<?= $options['timeout']; ?>" class="regular-text">
        </p>
        
        <p class="description"><?= __('Number of seconds how long the cache-files are valid. Default is one day. Leave empty or 0 to store as long as possible.', 'lazy-cache'); ?></p>
      
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-prefix"><?= __('Cache prefix', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cach[prefix]" type="text" id="lazy-cache-prefix" value="<?= $options['prefix']; ?>" class="regular-text">
        </p>
        
        <p class="description"><?= __('The prefix for the cache keys.', 'lazy-cache'); ?></p>
        
      </td>
    </tr>

  </tbody>
</table>