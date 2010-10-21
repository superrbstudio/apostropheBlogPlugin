<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>
<?php // Just echo the form. You might want to render the form fields differently ?>

<?php echo $form->renderHiddenFields() ?>

<h4 class="a-slot-form-title"><?php echo __('Blog Posts', array(), 'apostrophe') ?></h4>

<div class="a-form-row by-title-or-tag">
  <?php echo $form['title_or_tag']->render() ?>
</div>

<div class="a-form-row count">
	<?php echo $form['count']->renderLabel(__('Posts', array(), 'apostrophe')) ?>
	<div class="a-form-field">
		<?php echo $form['count']->render() ?>
		<div class="a-form-help collapsed"><?php echo $form['count']->renderHelp() ?></div>
	</div>
	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
</div>

<div class="a-form-row categories">
	<?php echo $form['categories_list']->renderLabel(__('Category', array(), 'apostrophe')) ?>
	<div class="a-form-field">
		<?php echo $form['categories_list']->render() ?>
		<div class="a-form-help collapsed"><?php echo $form['categories_list']->renderHelp() ?></div>
	</div>
	<div class="a-form-error"><?php echo $form['categories_list']->renderError() ?></div>
</div>

<div class="a-form-row conjunction">
  AND
</div>

<div class="a-form-row tags">
	<?php echo $form['tags_list']->renderLabel(__('Tags', array(), 'apostrophe')) ?>
	<div class="a-form-field">
		<?php echo $form['tags_list']->render() ?>
		<div class="a-form-help collapsed"><?php echo $form['tags_list']->renderHelp() ?></div>
	</div>
	<div class="a-form-error"><?php echo $form['tags_list']->renderError() ?></div>
</div>

<script type="text/javascript" charset="utf-8" src="/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js"></script>
<script type="text/javascript" src="/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
    aMultipleSelect('#a-<?php echo $form->getName() ?>', { 'choose-one': 'Add Categories' });
	  $('#<?php echo $form['search']->renderId() ?>').autocomplete('<?php echo url_for("@a_blog_admin_autocomplete") ?>');
	  $('#<?php echo $form['search']->renderId() ?>').result(function(event, data, formatted){
	    if (data) {
	      $('#<?php echo $form['blog_item']->renderId() ?>').val(data[1]);
	      $('#<?php echo $form['search']->renderId() ?>').val(data[2]);
	    }
	  });
	});
</script>