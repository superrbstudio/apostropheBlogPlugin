<?php use_helper('jQuery') ?>

<?php echo jq_form_remote_tag(array('url' => url_for('a_blog_admin_update',$a_blog_post) , 'update' => 'a-admin-blog-post-form'), array('id'=>'a-admin-form', 'class' => 'blog')) ?>

<?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="PUT" />
<?php endif; ?>

<?php // These are hidden here for now ?>
<div class="post-title post-slug option">
  <?php echo $form['title']->renderRow() ?>
  <?php echo $form['slug']->getWidget()->render('a_blog_post[slug]', $a_blog_post['slug']) ?>
  <?php echo $form['slug']->renderError() ?>
</div>

<?php include_partial('aBlogAdmin/optionsForm', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>

<?php // Dan: I moved this javascript to the bottom of editSuccess ?>