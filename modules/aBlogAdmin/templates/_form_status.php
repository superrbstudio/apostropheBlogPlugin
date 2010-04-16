<?php use_helper('I18N') ?>

<a href="#" class="a-btn big a-publish-post <?php echo ($a_blog_post['status'] == 'published')? 'published':'' ?>" onclick="return false;">
  <div class="publish"><?php echo __('Publish', array(), 'messages') ?></div>
  <div class="unpublish"><?php echo __('Unpublish', array(), 'messages') ?></div>
</a>

<div class="post-status option">
  <?php echo $form['status']->renderRow() ?>
</div>
