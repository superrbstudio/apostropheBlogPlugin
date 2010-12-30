<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
?>
<?php echo $form->renderHiddenFields() ?>

<h4 class="a-slot-form-title"><?php echo a_('Select Events') ?></h4>
<div class="a-form-row by-type meta">
  <?php $w = $form['title_or_tag'] ?>
  <input type="radio" id="<?php echo $w->renderId() ?>-tag" name="<?php echo $w->renderName() ?>" value="tags" <?php echo ($w->getValue() === "tags") ? 'checked' : '' ?> /> <label for="<?php echo $w->renderId() ?>-tag"><?php echo a_('By Category and Tag') ?></label>
  <div class="a-form-row count">
  	<div class="a-form-field">
      <?php echo a_('Show %count% Event(s)', array('%count%' => $form['count']->render())) ?>
				<div class="a-form-help collapsed"><?php echo $form['count']->renderHelp() ?></div>
  	</div>
  	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
  </div>

  <div class="a-form-row categories">
    <div class="a-form-field">
      <label class="a-multiple-select-label" for="<?php echo $form['categories_list']->renderId() ?>"><?php echo a_('Categorized')?></label><?php echo $form['categories_list']->render() ?>
    		<div class="a-form-help collapsed"><?php echo $form['categories_list']->renderHelp() ?></div>
		</div>
  	<div class="a-form-error"><?php echo $form['categories_list']->renderError() ?></div>
  </div>

  <div class="a-form-row conjunction">
    <?php echo a_('And') ?>
  </div>

  <div class="a-form-row tags">
  	<div class="a-form-field">
  	  <label for="<?php echo $form['tags_list']->renderId() ?>"><?php echo a_('Tagged') ?></label><?php echo $form['tags_list']->render() ?>
      <?php $options = array('popular-tags' => $popularTags, 'tags-label' => '', 'commit-selector' => '#a-slot-form-submit-' . $id, 'typeahead-url' => url_for('taggableComplete/complete')) ?>
      <?php if (sfConfig::get('app_a_all_tags', true)): ?>
        <?php $options['all-tags'] = $allTags ?>        
      <?php endif ?>
      <?php a_js_call('pkInlineTaggableWidget(?, ?)', '#' . $form['tags_list']->renderId(), $options) ?>
  	</div>
		<div class="a-form-help collapsed"><?php echo $form['tags_list']->renderHelp() ?></div>
  	<div class="a-form-error"><?php echo $form['tags_list']->renderError() ?></div>
  </div>
</div>
<hr />
<div class="a-form-row by-type title">
  <?php $w = $form['title_or_tag'] ?>
  <input type="radio" id="<?php echo $w->renderId() ?>-title" name="<?php echo $w->renderName() ?>" value="title" <?php echo ($w->getValue() === "title") ? 'checked' : '' ?> /> <label for="<?php echo $w->renderId() ?>-title"><?php echo a_('By Title') ?></label>

  <div class="a-form-row events">
    <?php echo $form['events']->render() ?>
  </div>
</div>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    aMultipleSelect('#a-<?php echo $form->getName() ?> .events', { 'autocomplete': <?php echo json_encode(url_for("aEventAdmin/search")) ?> });
    aMultipleSelect('#a-<?php echo $form->getName() ?> .categories', { 'choose-one': 'Add Categories' });
		var slotEditForm = $('#a-<?php echo $form->getName() ?>')
		var editStates = slotEditForm.find('.a-form-row.by-type input[type="radio"]');
		var editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
		slotEditForm.addClass('a-options dropshadow editState-' + editState );
			editStates.live('click', function(){
			 	editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
				slotEditForm.removeClass('editState-title').removeClass('editState-tags').addClass('editState-'+editState);
			});
	});
</script>