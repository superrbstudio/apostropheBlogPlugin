<div class="published section">

  <a href="#" class="a-btn big a-publish-post <?php echo ($a_blog_post['status'] == 'published')? 'published':'' ?>" onclick="return false;">
    <div class="publish"><?php echo __('Publish', array(), 'messages') ?></div>
    <div class="unpublish"><?php echo __('Unpublish', array(), 'messages') ?></div>
  </a>

  <div class="post-status option">
    <?php echo $form['status']->renderRow() ?>
  </div>

  <?php echo __('Publish now or', array(), 'messages') ?>  <a href="#" onclick="return false;" class="post-date-toggle a-sidebar-toggle"><?php echo __('set a date', array(), 'messages') ?></a>

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
      <li><a href="#" onclick="checkAndSetPublish('<?php echo $blog_post_url ?>'); return false;" class="a-btn a-save">Save</a></li>
      <li><a href="#" onclick="checkAndSetPublish('<?php echo $blog_post_url ?>'); return false;" class="a-btn a-cancel">Cancel</a></li>
    </ul>
  </div>

</div>