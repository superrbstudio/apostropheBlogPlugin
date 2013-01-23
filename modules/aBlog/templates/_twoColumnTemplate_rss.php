<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
?>

<?php if (sfConfig::get('app_aBlog_feedThumbnails', false)): ?>
  <?php echo $aBlogPost->getThumbnailMarkup() ?>
<?php endif ?>

<?php // No redundant title and date info. cnn.com doesn't do it either. ?>

<?php echo $aBlogPost->getRichTextForAreas('blog-body', sfConfig::get('app_aBlog_feedExcerpts') ? sfConfig::get('app_aBlog_feedExcerptLength', 30) : null) ?>
