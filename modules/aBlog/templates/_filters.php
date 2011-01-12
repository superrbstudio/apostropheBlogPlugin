<?php $type = $sf_data->getRaw('type') ?>
<?php $typePlural = $sf_data->getRaw('typePlural') ?>
<?php $url = $sf_data->getRaw('url') ?>
<?php $params = $sf_data->getRaw('params') ?>
<?php $count = $sf_data->getRaw('count') ?>

<div class="a-ui a-blog-browser">

	<?php if ($sf_params->get('year')): ?>
	  <?php if (sfConfig::get('app_a_pretty_english_dates')): ?>
  	  <?php if ($sf_params->get('day')): ?>
  	    <?php $prevLabel = aDate::pretty($params['prev']['year'] . '-' . $params['prev']['month'] . '-' . $params['prev']['day']) ?>
  	    <?php $nextLabel = aDate::pretty($params['next']['year'] . '-' . $params['next']['month'] . '-' . $params['next']['day']) ?>
  	  <?php elseif ($sf_params->get('month')): ?>
  	    <?php $prevLabel = aDate::pretty($params['prev']['year'] . '-' . $params['prev']['month'] . '-01') ?>
  	    <?php $prevLabel = preg_replace('/\s+\d+/', '', $prevLabel) ?>
  	    <?php $nextLabel = aDate::pretty($params['next']['year'] . '-' . $params['next']['month'] . '-01') ?>
  	    <?php $nextLabel = preg_replace('/\s+\d+/', '', $nextLabel) ?>
  	  <?php else: ?>
  	    <?php $prevLabel = $params['prev']['year'] ?>
  	    <?php $nextLabel = $params['next']['year'] ?>
  	  <?php endif ?>
	  <?php else: ?>
  	  <?php if ($sf_params->get('day')): ?>
  	    <?php $prevLabel = $params['prev']['year'] . '-' . $params['prev']['month'] . '-' . $params['prev']['day'] ?>
  	    <?php $nextLabel = $params['next']['year'] . '-' . $params['next']['month'] . '-' . $params['next']['day'] ?>
  	  <?php elseif ($sf_params->get('month')): ?>
  	    <?php $prevLabel = $params['prev']['year'] . '-' . $params['prev']['month'] ?>
  	    <?php $nextLabel = $params['next']['year'] . '-' . $params['next']['month'] ?>
  	  <?php else: ?>
  	    <?php $prevLabel = $params['prev']['year'] ?>
  	    <?php $nextLabel = $params['next']['year'] ?>
  	  <?php endif ?>
  	<?php endif ?>
		<ul class="a-ui a-controls a-blog-browser-controls">
	  	<li><?php echo a_button('&#8810; ' . $prevLabel, url_for($url.'?'.http_build_query($params['prev'])), array('icon','alt','a-arrow-left', 'no-bg')) ?></li>
	  	<li><?php echo a_button($nextLabel . ' &#8811;', url_for($url.'?'.http_build_query($params['next'])), array('icon','alt','a-arrow-right', 'no-bg')) ?></li>
		</ul>
	<?php endif ?>

	<?php $filters = array() ?>
	<?php $date = $sf_params->get('day') . ' ' . (($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '') . ' ' . $sf_params->get('year') ?>
	<?php $filterUrl = aUrl::addParams($url, array('year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'cat' => $sf_params->get('cat'), 'tag' => $sf_params->get('tag'))) ?>

	<?php if ($sf_params->get('year') > 0): ?>
	  <?php $filters[] = a_('for %date%', array('%date%' => a_remove_filter_button($date, $filterUrl, array('year', 'month', 'day')))) ?>
	<?php endif ?>

	<?php if (strlen($sf_params->get('q'))): ?>
	  <?php $filters[] = a_('matching the search %search%', array('%search%' => a_remove_filter_button($sf_params->get('q'), $filterUrl, 'q'))) ?>
	<?php endif ?>

	<?php if (strlen($sf_params->get('cat'))): ?>
	  <?php $category = Doctrine::getTable('aCategory')->findOneBySlug($sf_params->get('cat')) ?>
	  <?php if ($category): ?>
	    <?php $filters[] = a_('in the category %category%', array('%category%' => a_remove_filter_button($category->name, $filterUrl, 'cat'))) ?>
	  <?php endif ?>
	<?php endif ?>

	<?php if (strlen($sf_params->get('tag'))): ?>
	  <?php $filters[] = a_('with the tag %tag%', array('%tag%' => a_remove_filter_button($sf_params->get('tag'), $filterUrl, 'tag'))) ?>
	<?php endif ?>

  <h3 class="a-blog-filters browser">
	<?php if (count($filters)): ?>
		<?php // Pluralize $type ?>
		<?php $type = ($count != 1) ? $typePlural : $type  ?>
		<?php echo a_('You are viewing %count% %type% %filters%', array('%count%' => $count, '%type%' => $type, '%filters%' => implode(' ', $filters))) ?>
	<?php else: ?>
		<?php if ($count): ?>
			<?php echo a_('You are viewing all %type%', array('%type%' => $typePlural)) ?>
		<?php else: ?>
			<?php echo a_('There are no %type%', array('%type%' => $typePlural)) ?>			
		<?php endif ?>
	<?php endif ?>
	</h3>
</div>
