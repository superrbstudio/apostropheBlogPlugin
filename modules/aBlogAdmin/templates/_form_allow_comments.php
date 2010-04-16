<?php use_helper('I18N') ?>

<h4>
	<a href="#" class="allow_comments_toggle <?php echo ($a_blog_post['allow_comments'])? 'enabled' : 'disabled' ?>"><span class="enabled" title="<?php echo __('Click to disable comments', array(), 'apostrophe_blog') ?>"><?php echo __('Comments are enabled', array(), 'apostrophe_blog') ?> </span><span class="disabled" title="<?php echo __('Click to enable comments', array(), 'apostrophe_blog') ?>"><?php echo __('Comments are disabled', array(), 'apostrophe_blog') ?></span></a>
</h4> 

<div class="allow_comments option">
<?php echo $form['allow_comments']->render() ?>
<?php echo $form['allow_comments']->renderError() ?>
</div>