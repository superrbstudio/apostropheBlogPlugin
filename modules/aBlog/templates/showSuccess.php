<?php slot('body_class') ?>a-blog-engine <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<div id="a-subnav" class="blog">
	<div id="a-subnav-top" class="a-subnav-top"></div>
	<div class="a-subnav-wrapper">
    <?php include_component('aBlog', 'tagSidebar', array('params' => $params, 'dateRange' => '')) ?>
	</div>		
	<div id="a-subnav-bottom" class="a-subnav-bottom"></div>
</div>

<div id="a-blog-main" class="a-blog-main">
  <?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post)); ?>
</div>