<?php use_helper('I18N') ?>

<?php echo __('Publish now or', array(), 'apostrophe_blog') ?>  <a href="#" onclick="return false;" class="post-date-toggle a-sidebar-toggle"><?php echo __('set a date', array(), 'apostrophe_blog') ?></a>

<div class="post-published-at option">
  <?php echo $form['published_at']->render() ?>
  <?php echo $form['published_at']->renderError() ?>

  <?php
  // Dan:
  // All of a sudden we have save and cancel buttons now.
  // So apparently when you click save it makes this change
  // If you click cancel it some how restores it to 'Publish Now' – It doesn't just simply hide this options pane
  ?>
  <ul class="a-controls">
    <li><a href="#" onclick="checkAndSetPublish('<?php echo $blog_post_url ?>'); return false;" class="a-btn a-save"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
    <li><a href="#" onclick="checkAndSetPublish('<?php echo $blog_post_url ?>'); return false;" class="a-btn a-cancel"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
  </ul>
</div>