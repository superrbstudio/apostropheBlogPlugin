<?php slot('body_class') ?>a-blog a-blog-events <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
<div class="a-subnav-wrapper blog">
	
	<div class="a-subnav-inner">
    <?php include_component('aCalendar', 'tagSidebar', array('params' => $params, 'dateRange' => '')) ?>
	</div>		
	
</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
  <?php echo include_partial('aCalendar/event', array('a_blog_event' => $a_blog_event)); ?>
</div>