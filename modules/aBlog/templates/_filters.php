<?php $type = $sf_data->getRaw('type') ?>
<?php $url = $sf_data->getRaw('url') ?>
<?php $params = $sf_data->getRaw('params') ?>
<?php $count = $sf_data->getRaw('count') ?>

<div class="a-ui a-blog-browser">

	<?php if ($sf_params->get('year')): ?>
		<ul class="a-ui a-controls a-blog-browser-controls">
	  	<li><?php echo a_button(a_('Previous'), url_for($url.'?'.http_build_query($params['prev'])), array('icon','alt','a-arrow-left', 'no-bg')) ?></li>
	  	<li><?php echo a_button(a_('Next'), url_for($url.'?'.http_build_query($params['next'])), array('icon','alt','a-arrow-right', 'no-bg')) ?></li>
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

	<?php if (count($filters)): ?>
		<?php // pluralize $type ?>
		<?php $type = ($count > 1) ? $type.'s' : $type  ?>
	  <h3 class="a-blog-filters browser"><?php echo a_('You are viewing %count% %type% %filters%', array('%count%' => $count, '%type%' => $type, '%filters%' => implode(' ', $filters))) ?></h3>
	<?php endif ?>

</div>