<?php // Saving either of these forms updates both (because title can affect slug) ?>
<?php use_helper('a') ?>
<form method="POST" action="<?php echo url_for('a_blog_admin_updateTitle', $a_event) ?>" id="a-blog-item-title-interface">
	<input type="text" name="title" class="a-title" value="<?php echo a_entities(($a_event->title == 'untitled')? '':$a_event->title) ?>" />
  <ul class="a-ui a-controls blog-title">
    <li><?php echo a_anchor_submit_button(a_('Save'), array('a-save', 'big')) ?></li>
    <li><a href="#" class="a-btn icon a-cancel no-label big"><span class="icon"></span><?php echo a_('Cancel') ?></a></li>
  </ul>
</form>

<form method="POST" action="<?php echo url_for('a_blog_admin_updateSlug', $a_event) ?>" id="a-blog-item-permalink-interface">
	<h6>Permalink:</h6>
	<div class="a-blog-item-permalink-wrapper url">
    <span><?php echo aTools::urlForPage($a_event->findBestEngine()->getSlug()).'/' ?><?php echo date('Y/m/d/', strtotime($a_event->getPublishedAt())) ?></span>
	</div>
	<div class="a-blog-item-permalink-wrapper slug">
		<input type="text" name="slug" class="a-slug" value="<?php echo a_entities($a_event->slug) ?>">
	  <ul class="a-ui a-controls blog-slug">
	    <li><?php echo a_anchor_submit_button(a_('Save'), array('a-save', 'mini')) ?></li>
	    <li><a href="#" class="a-btn icon a-cancel no-label mini"><span class="icon"></span><?php echo a_('Cancel') ?></a></li>
	  </ul>
	</div>
</form>
<?php a_js_call('aBlogEnableTitle()') ?>
<?php a_js_call('aBlogEnableSlug()') ?>
