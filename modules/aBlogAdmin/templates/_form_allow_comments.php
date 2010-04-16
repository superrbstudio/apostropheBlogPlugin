<h4>Comments: <span><?php echo ($a_blog_post['allow_comments'])? 'Enabled': 'Disabled' ?></span></h4> 
<?php echo $form['allow_comments']->render() ?>
<?php echo $form['allow_comments']->renderError() ?>