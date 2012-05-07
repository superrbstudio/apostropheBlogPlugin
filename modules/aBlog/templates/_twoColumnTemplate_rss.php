<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
?>
<?php // No redundant title and date info. cnn.com doesn't do it either. ?>
<?php foreach($aBlogPost->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getBasicHtml() ?>
<?php endforeach ?>
