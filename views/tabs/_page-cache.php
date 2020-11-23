<h2 class="title"><?= __('Page cache', 'lazy-cache'); ?></h2>

<table class="form-table">
  <tbody>

    <tr>
      <th scope="row">
        <label for="lazy-cache-enable-page-cache"><?= __('Enable page cache', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[enablePageCache]" type="hidden" value="0">
          <input name="lazy-cache[enablePageCache]" type="checkbox" id="lazy-cache-enable-page-cache" <?= $options['enablePageCache'] ? 'checked' : ''; ?> value="1" />
        </p>
        
      </td>
    </tr>

    <tr>
      <th scope="row">
        <label for="lazy-cache-post-types"><?= __('Post types', 'lazy-cache'); ?></label>
      </th>
      <td>

        <?php foreach ($postTypes as $postType => $object) : ?>

        <p>
          <label>
            <input name="lazy-cache[postTypes][]" type="checkbox" value="<?= $postType; ?>" <?= in_array($postType, $options['postTypes']) ? 'checked' : ''; ?>> <?= $object->label; ?>
          </label>
        </p>

        <?php endforeach; ?>

      </td>
    </tr>

  </tbody>
</table>