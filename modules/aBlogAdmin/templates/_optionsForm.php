<?php echo $form->renderHiddenFields() ?>

<div class="post-options">
	<h2>Post Options</h2>
	<?php echo $form['author_id']->renderRow() ?>
</div>

<hr />

<div class="published-at">
	<h2>Published At</h2>
	<?php echo $form['published_at']->render() ?>
	<?php echo $form['published_at']->renderError() ?>	
</div>

<hr />

<div class="categories">
	<h2>Categories</h2>
	<?php echo $form['categories_list']->renderRow() ?>
</div>

<hr />

<div class="tags">
	<h2>Tags</h2>
	<?php echo $form['tags']->render() ?>
	<?php echo $form['tags']->renderError() ?>
</div>

<hr />

<div class="permissions">
	<h2>Post Permissions</h2>
	<?php echo $form['status']->renderRow() ?>
	<?php echo $form['public']->renderRow() ?>
</div>

<hr />

<div class="editors">
	<h2>Editors</h2>
	<?php echo $form['editors_list']->renderRow()?>
</div>