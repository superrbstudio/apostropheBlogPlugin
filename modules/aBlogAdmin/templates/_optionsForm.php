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

<div class="categories section">
	<h2>Categories</h2>
	<?php echo $form['categories_list']->renderRow() ?>
</div>

<hr />

<div class="tags section">
	<h2>Tags</h2>
	<?php echo $form['tags']->render() ?>
	<?php echo $form['tags']->renderError() ?>
</div>

<hr />

<div class="editors section">
	<h2>Editors</h2>
	<?php echo $form['editors_list']->renderRow()?>
</div>