<?php use_helper('I18N') ?>

<div class="post-author">
  <h4><?php echo __('Author', array(), 'apostrophe_blog') ?>: <span><?php echo $a_blog_post->Author ?></span></h4>
  <?php // We aren't letting them switch the user as per Rick's design ?>
  <?php // echo $form['author_id']->renderRow() ?>
</div>
