<?php slot('body_class') ?>a-blog a-blog-posts <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
<div id="a-subnav" class="blog">
	
	<div class="a-subnav-wrapper">
    <?php include_component('aBlog', 'tagSidebar', array('params' => $params, 'dateRange' => '')) ?>
	</div>		
	
</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
  <?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post)); ?>
</div>