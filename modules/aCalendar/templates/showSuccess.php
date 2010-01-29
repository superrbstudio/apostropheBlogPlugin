<?php slot('body_class') ?>a-blog-calendar <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<div id="a-subnav" class="blog">
	<div id="a-subnav-top" class="a-subnav-top"></div>
	<div class="a-subnav-wrapper">
    <?php include_component('aCalendar', 'tagSidebar', array('params' => $params, 'dateRange' => '')) ?>
	</div>		
	<div id="a-subnav-bottom" class="a-subnav-bottom"></div>
</div>

<div class="a-blog-main">
  <?php echo include_partial('aCalendar/event', array('a_blog_event' => $a_blog_event)); ?>
</div>