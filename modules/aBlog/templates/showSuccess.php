<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
?>
<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories, 'reset' => true, 'noFeed' => true)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<?php echo include_partial('aBlog/post', array('a_blog_post' => $aBlogPost)) ?>

<?php  if (sfConfig::get('app_aBlog_disqus_enabled', true)): ?>
<?php include_partial('aBlog/disqus', array('id' => $aBlogPost->getId(), )) ?>
<?php endif ?>