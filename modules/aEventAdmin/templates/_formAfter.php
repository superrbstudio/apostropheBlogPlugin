<?php
  // Here are the variables you might need, localized without escaping
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>

<?php // This is how to output a new section with similar styling ?>
<?php // NOTE: COMMENTED OUT with if (0), just an example ?>

<?php if (0): ?>
<hr class="a-hr" />
<div class="section a-form-row">
  <h4><?php echo a_('My Section') ?></h4>
	<div>
	<?php echo $form['my-extra-column']->render() ?>
	<?php echo $form['my-extra-column']->renderError() ?>
	</div>
</div>
<?php endif ?>