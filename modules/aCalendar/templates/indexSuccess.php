<?php # EVENTS ========================================================= ?>

<?php slot('body_class') ?>a-blog a-blog-events <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
<div id="a-subnav" class="blog">
	
	<div class="a-subnav-wrapper">
		<?php include_component('aCalendar', 'tagSidebar', array('params' => $params, 'dateRange' => $dateRange)) ?>
	</div>		
	
</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
  <?php if ($sf_params->get('year')): ?>
  <h2><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h2>
  <ul class="a-controls a-blog-browser-controls">
    <li><?php echo link_to('Previous', 'aCalendar/index?'.http_build_query($params['prev']), array('class' => 'a-btn icon a-arrow-left nobg', )) ?></li>
    <li><?php echo link_to('Next', 'aCalendar/index?'.http_build_query($params['next']), array('class' => 'a-btn icon a-arrow-right nobg', )) ?></li>
  </ul>
  <?php endif ?>

  <?php if ($a_blog_events->haveToPaginate()): ?>
  	<?php echo include_partial('aPager/pager', array('pager' => $a_blog_events, 'pagerUrl' => url_for('aCalendar/index?'. http_build_query($params['pagination'])))); ?>
	<?php endif ?>

  <?php foreach ($a_blog_events->getResults() as $a_blog_event): ?>
  <?php echo include_partial('aCalendar/event', array('a_blog_event' => $a_blog_event, 'excerpt' => 'true')); ?>
  <?php endforeach ?>

  <?php if (!count($a_blog_events->getResults())): ?>
  <?php include_partial('aCalendar/noresults') ?>
  <?php endif ?>

  <?php if ($a_blog_events->haveToPaginate()): ?>
  	<?php echo include_partial('aPager/pager', array('pager' => $a_blog_events, 'pagerUrl' => url_for('aCalendar/index?'. http_build_query($params['pagination'])))); ?>
	<?php endif ?>
</div>
