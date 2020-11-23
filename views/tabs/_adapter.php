<h2 class="title"><?= __($adapters[$options['adapterClass']], 'lazy-cache'); ?></h2>

<?php include __DIR__.'/adapter/_'.$options['adapterClass']::key().'.php'; ?>
