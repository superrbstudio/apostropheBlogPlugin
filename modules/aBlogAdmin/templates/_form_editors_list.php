
  <div class="post-editors">
    <a href="#" onclick="return false;" class="post-editors-toggle a-sidebar-toggle"><?php echo __('allow others to edit this post', array(), 'messages') ?></a>
    <div class="post-editors-options option" id="editors-section">
      <h4>Editors:</h4>
      <?php
      // Dan:
      // The multiple-select needs to go away
      // This should be the multi-select just like categories only it's hidden
      ?>
      <?php echo $form['editors_list']->render()?>
      <?php echo $form['editors_list']->renderError() ?>
    </div>
  </div>