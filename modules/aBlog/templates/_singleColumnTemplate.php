<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
	$admin = ($sf_params->get('module') == 'aBlogAdmin') ? true : false;
?>

<?php if (!$admin): ?>
	<h3 class="a-blog-item-title">
	  <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
		<?php if ($a_blog_post['status'] == 'draft'): ?>
			<span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
		<?php endif ?>
	</h3>
	<?php include_partial('aBlog/meta', array('a_blog_post' => $a_blog_post)) ?>
<?php endif ?>

<div class="a-blog-item-content">

  <?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
  <?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>
  
  <?php include_component('a', 'standardArea', array('name' => 'blog-body', 'edit' => $edit, 'toolbar' => 'main', 'slug' => $a_blog_post->Page->slug, 'width' => 480, 'minusSlots' => array('aBlog', 'aEvent'))) ?>

	<?php if (!$admin): ?>
		<?php include_partial('aBlog/tags', array('aBlogItem' => $a_blog_post)) ?>
		<?php include_partial('aBlog/addThis', array('aBlogItem' => $a_blog_post)) ?>
	<?php endif ?>

</div>