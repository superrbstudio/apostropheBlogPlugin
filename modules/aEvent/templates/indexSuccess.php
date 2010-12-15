<?php
  // Compatible with sf_escaping_strategy: true
  $blogCategories = isset($blogCategories) ? $sf_data->getRaw('blogCategories') : null;
  $dateRange = isset($dateRange) ? $sf_data->getRaw('dateRange') : null;
  $pager = isset($pager) ? $sf_data->getRaw('pager') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
?>
<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aEvent', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories, 'calendar' => $calendar, )) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
	<div class="a-blog-heading"> 
	  <?php a_area('blog-heading', array('area_add_content_label' => a_('Add Heading Content'), 'allowed_types' => array('aRichText', 'aSlideshow', 'aSmartSlideshow'))) ?>
	  <?php if ($page->userHasPrivilege('edit')): ?>
	    <div class="a-help">
	      The heading appears before the actual events. Use the "New Event" button to add events.
	    </div>
	  <?php endif ?>
    <?php if ($sf_params->get('year')): ?>
  		<h3><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h3>
  	  <ul class="a-ui a-controls a-blog-browser-controls">
  	    <li><?php echo link_to('<span class="icon"></span>'.a_('Previous'), 'aEvent/index?'.http_build_query($params['prev']), array('class' => 'a-arrow-btn icon a-arrow-left', )) ?></li>
  	    <li><?php echo link_to('<span class="icon"></span>'.a_('Next'), 'aEvent/index?'.http_build_query($params['next']), array('class' => 'a-arrow-btn icon a-arrow-right', )) ?></li>
  	  </ul>
  	<?php endif ?>
	</div>

  <?php foreach ($pager->getResults() as $a_event): ?>
  	<?php echo include_partial('aEvent/post', array('a_event' => $a_event, 'edit' => false, )) ?>
  	<hr />
  <?php endforeach ?>

    <?php if ($pager->haveToPaginate()): ?>
 		<?php echo include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => url_for('aEvent/index?'. http_build_query($params['pagination'])))); ?>
  <?php endif ?>
</div>