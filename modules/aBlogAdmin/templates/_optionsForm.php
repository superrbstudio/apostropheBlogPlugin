<?php use_helper('I18N') ?>
<?php $blog_post_url = url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>

<?php echo $form->renderHiddenFields() ?>
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

<hr />

<div class="author section">

  <div class="post-author">
    <h4>Author:  <span><?php echo $a_blog_post->Author ?></span></h4>
    <?php // We aren't letting them switch the user as per Rick's design ?>
    <?php // echo $form['author_id']->renderRow() ?>
  </div>

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

</div>

<hr />

<div class="categories section" id="categories-section">
  <h4>Categories</h4>
  <?php echo $form['categories_list']->render() ?>
  <?php echo $form['categories_list']->renderError() ?>
</div>

<hr />

<div class="tags section">
  <h4>Tags</h4>
  <?php echo $form['tags']->render() ?>
  <?php echo $form['tags']->renderError() ?>
  <script src='/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js'></script>
  <script type="text/javascript" charset="utf-8">
    $(function() {
      pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
    });
  </script>
  <?php include_component('aBlogAdmin','tagList', array('a_blog_post' => $form->getObject())) ?>
</div>

<?php if(isset($form['template'])): ?>
<hr />
<div class="template section">
  <h4>Template</h4>
  <?php echo $form['template']->render() ?>
  <?php echo $form['template']->renderError() ?>
</div>
<?php endif ?>

<?php if (1): ?>
<?php // To Do: Comments are not built yet ?>
  <div class="comments section">
    <?php  echo $form['allow_comments']->renderRow() ?>
  </div>
<?php endif ?>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function(){
    $('#a-admin-form').change(function(event) {
      updateBlog(event);
    });

    checkAndSetPublish('<?php echo $blog_post_url ?>');

    $('.a-sidebar-toggle').click(function(){
      $(this).toggleClass('open').next().toggle();
    })

    aMultipleSelect('#categories-section', { 'choose-one': 'Add Categories', 'add': 'New Category'});
    aMultipleSelect('#editors-section', { 'choose-one': 'Add Editors', });
  });
</script>