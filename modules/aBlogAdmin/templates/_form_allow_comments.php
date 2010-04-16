<?php use_helper('I18N') ?>

<h4><?php echo __('Comments', array(), 'apostrophe_blog') ?>: <span><?php echo ($a_blog_post['allow_comments'])? __('Enabled', array(), 'apostrophe_blog') : __('Disabled', array(), 'apostrophe_blog') ?></span></h4> 

<?php echo $form['allow_comments']->render() ?>
<?php echo $form['allow_comments']->renderError() ?>