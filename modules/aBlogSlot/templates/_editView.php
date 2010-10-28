<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
  $popularTags = isset($popularTags) ? $sf_data->getRaw('popularTags') : array();
  $allTags = isset($allTags) ? $sf_data->getRaw('allTags') : array();
?>
<?php echo $form->renderHiddenFields() ?>

<h4 class="a-slot-form-title"><?php echo a_('Select Blog Posts') ?></h4>

<div class="a-form-row by-type">
  <?php $w = $form['title_or_tag'] ?>
  <input type="radio" id="<?php echo $w->renderId() ?>-title" name="<?php echo $w->renderName() ?>" value="title" <?php echo ($w->getValue() === "title") ? 'checked' : '' ?> /> <label for="<?php echo $w->renderId() ?>-title"><?php echo a_('By Title') ?></label>

  <div class="a-form-row blog-posts">
    <?php echo $form['blog_posts']->render() ?>
  </div>
</div>

<div class="a-form-row by-type">
  <?php $w = $form['title_or_tag'] ?>
  <input type="radio" id="<?php echo $w->renderId() ?>-tag" name="<?php echo $w->renderName() ?>" value="tags" <?php echo ($w->getValue() === "tags") ? 'checked' : '' ?> /> <label for="<?php echo $w->renderId() ?>-tag"><?php echo a_('By Category and Tag') ?></label>

  <div class="a-form-row count">
  	<div class="a-form-field">
      <?php echo a_('Show %count% Post(s)', array('%count%' => $form['count']->render())) ?>
  	</div>
  	<div class="a-form-error"><?php echo $form['count']->renderError() ?></div>
  </div>

  <div class="a-form-row categories">
    <div class="a-form-field">
      <label class="a-multiple-select-label" for="<?php echo $form['categories_list']->renderId() ?>"><?php echo a_('Categorized')?></label><?php echo $form['categories_list']->render() ?>
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
  	<div class="a-form-error"><?php echo $form['tags_list']->renderError() ?></div>
  </div>
</div>

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    pkInlineTaggableWidget(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
    aMultipleSelect('#a-<?php echo $form->getName() ?> .blog-posts', { 'autocomplete': <?php echo json_encode(url_for("aBlogAdmin/search")) ?> });
    aMultipleSelect('#a-<?php echo $form->getName() ?> .categories', { 'choose-one': 'Add Categories' });
	});
</script>