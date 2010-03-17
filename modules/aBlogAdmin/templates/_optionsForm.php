<?php echo $form->renderHiddenFields() ?>
<h2>Post Options</h2>
<p><?php echo $form['author_id']->renderRow() ?></p>
<hr>
<h2>Published At</h2>
<p><?php echo $form['published_at']->renderRow() ?></p>
<hr>
<h3>Categories</h3>
<p><?php echo $form['categories_list']->renderRow() ?></p>
<hr>
<h3>Tags</h3>
<p><?php echo $form['tags']->renderRow() ?></p>
<hr>
<h3>Post Permissions</h3>
<p><?php echo $form['status']->renderRow() ?></p>
<p><?php echo $form['public']->renderRow() ?></p>
<hr>
<h3>Editors</h3>
<p><?php echo $form['editors_list']->renderRow()?></p>