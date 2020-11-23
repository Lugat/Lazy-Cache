<h2 class="title"><?= __('Extras', 'lazy-cache'); ?></h2>

<p><?= __('Lazy Cache offers some extra features which can be activated.', 'lazy-cache'); ?><p>

<table class="form-table">
  <tbody>

    <tr>
      <th scope="row">
        <label for="lazy-cache-minify-html"><?= __('Minify HTML', 'lazy-cache'); ?></label>
      </th>
      <td>
        
        <p>
          <input name="lazy-cache[minifyHtml]" type="hidden" value="0">
          <input name="lazy-cache[minifyHtml]" type="checkbox" id="lazy-cache-minify-html" <?= $options['minifyHtml'] == 1 ? 'checked' : ''; ?> value="1" />
        </p>
        
      </td>
    </tr>

  </tbody>
</table>