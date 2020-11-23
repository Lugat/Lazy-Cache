<div class="wrap" id="lazy-cache">

  <h1>Lazy Cache</h1>
  
  <ul class="subsubsub">

    <li>
      <a href="#general"><?= __('General', 'lazy-cache'); ?></a> |
    </li>
    
    <li>
      <a href="#page-cache"><?= __('Page-Cache', 'lazy-cache'); ?></a> |
    </li>
    
    <li>
      <a href="#rules"><?= __('Rules', 'lazy-cache'); ?></a> |
    </li>

    <li>
      <a href="#extras"><?= __('Extras', 'lazy-cache'); ?></a> |
    </li>
    
    <li>
      <a href="#adapter"><?= __($adapters[$options['adapterClass']], 'lazy-cache'); ?></a>
    </li>

  </ul>
  
  <div class="clear"></div>
  
  <form method="post" novalidate="novalidate">
          
    <div id="general">
      <?php include __DIR__.'/tabs/_general.php'; ?>
    </div>

    <div id="page-cache" style="display:none">
      <?php include __DIR__.'/tabs/_page-cache.php'; ?>
    </div>

    <div id="rules" style="display:none">
      <?php include __DIR__.'/tabs/_rules.php'; ?>
    </div>
    
    <div id="extras" style="display:none">
      <?php include __DIR__.'/tabs/_extras.php'; ?>
    </div>

    <div id="adapter" style="display:none">
      <?php include __DIR__.'/tabs/_adapter.php'; ?>
    </div>
    
    <hr />
    
    <p class="submit">
      <button type="submit" name="lazy-cache-action" value="update" id="submit" class="button button-primary"><?= __('Save', 'lazy-cache'); ?></button>
      <button type="submit" name="lazy-cache-action" value="reset" id="submit" class="button button-secondary"><?= __('Reset', 'lazy-cache'); ?></button>
    </p>

  </form>

</div>