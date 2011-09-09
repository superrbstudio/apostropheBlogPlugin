<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
	$admin = ($sf_params->get('module') == 'aEventAdmin') ? true : false;
?>

<?php if (!$admin): ?>
	<h3 class="a-blog-item-title">
	  <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
		<?php if ($a_event['status'] == 'draft'): ?>
			<span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
		<?php endif ?>
	</h3>
	<?php include_partial('aEvent/meta', array('aEvent' => $a_event)) ?>
<?php endif ?>

<div class="a-blog-item-content">

  <?php // Areas cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
  <?php include_component('a', 'standardArea', array('name' => 'blog-body', 'edit' => $edit, 'toolbar' => 'main', 'slug' => $a_event->Page->slug, 'width' => 480, 'minusSlots' => array('aBlog', 'aEvent'))) ?>

	<?php if (!$admin): ?>
	<?php include_partial('aBlog/tags', array('aBlogItem' => $a_event)) ?>
	<?php include_partial('aBlog/addThis', array('aBlogItem' => $a_event)) ?>
	<?php endif ?>

</div>
<?php slot('disqus_needed', 1) ?>