<?php slot('body_class') ?>a-blog a-blog-events <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
<div id="a-subnav" class="blog">
	
	<div class="a-subnav-wrapper">
    <?php include_component('aCalendar', 'tagSidebar', array('params' => $params, 'dateRange' => '')) ?>
	</div>		
	
</div>
<?php end_slot() ?>

<div class="a-blog-main">
  <?php echo include_partial('aCalendar/event', array('a_blog_event' => $a_blog_event)); ?>
</div>