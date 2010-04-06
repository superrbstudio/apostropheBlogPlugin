<?php echo rand(0, 500) ?>
<?php use_helper('jQuery') ?>
<?php echo jq_form_remote_tag(array('url' => '@a_blog_admin_update?slug='.$a_blog_post['slug'], 'update' => 'a-admin-blog-post-form'), array('id'=>'a-admin-form')) ?>
<?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="PUT" />
<?php endif; ?>
<div class="a-blog-post-title">
  <h2>Title</h2>
  <?php echo $form['title']->render() ?>
  <?php echo $form['title']->renderError() ?>
</div>

<div class="a-blog-post-slug">
  <h2>Permalink</h2>
  <?php echo $form['slug']->getWidget()->render('a_blog_post[slug]', $a_blog_post['slug']) ?>
  <?php //echo $form['slug']->render() ?>
  <?php echo $form['slug']->renderError() ?>
</div>

<?php include_partial('aBlogAdmin/optionsForm', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>

<?php // Dan: I moved this javascript to the bottom of editSuccess ?>