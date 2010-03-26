<?php echo $form->renderHiddenFields() ?>

<div class="post-options section">
	<h2>Post Options</h2>

	<div class="option">
		<?php echo $form['author_id']->renderRow() ?>
	</div>

	<div class="option">
		<?php echo $form['status']->renderRow() ?>
	</div>

	<div class="option">
		<?php echo $form['allow_comments']->renderRow() ?>
	</div>
  
  <div class="option">
    <?php echo $form['template']->renderRow() ?>
  </div>
	
</div>

<hr />

<div class="published-at section">
	<h2>Published At</h2>
	<?php echo $form['published_at']->render() ?>
	<?php echo $form['published_at']->renderError() ?>	
</div>

<hr />

<div class="categories section" id="categories-section">
	<h2>Categories</h2>
	<?php echo $form['categories_list']->renderRow() ?>
</div>


<hr />

<div class="tags section">
	<h2>Tags</h2>
	<?php echo $form['tags']->render() ?>
	<?php echo $form['tags']->renderError() ?>
  <?php include_component('aBlogAdmin','tagList', array('a_blog_post' => $form->getObject())) ?>
</div>

<hr />

<div class="editors section" id="editors-section">
	<h2>Editors</h2>
	<?php echo $form['editors_list']->renderRow()?>
</div>

<script type="text/javascript">
  $(function() {
    aMultipleSelect('#categories-section', { 'choose-one': 'Add Categories', })
    aMultipleSelect('#editors-section', { 'choose-one': 'Add Editors', })
  });
</script>