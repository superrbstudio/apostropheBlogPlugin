<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('aBlogAdmin/assets') ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
  <?php include_partial('aBlogAdmin/form_bar', array('title' => __('Edit Blog Post', array(), 'messages'))) ?>

	<?php slot('a-subnav') ?>
	  <div id="a-subnav" class="blog">
	    <div id="a-subnav-top" class="a-subnav-top"></div>
	      <div class="a-subnav-wrapper">
	        <ul class="a-admin-action-controls">
	          <li><?php echo link_to('New Post', '@a_blog_admin_new', array('class' => 'a-btn icon a-add')) ?></li>
	          <li><?php echo link_to('Edit Categories', '@a_blog_category_admin') ?></li>
	          <li><?php echo link_to('Edit Posts', '@a_blog_admin') ?></li>
	          <li><?php echo link_to('Edit Comments', '@a_comment_admin') ?></li>
	        </ul>
	      </div> 
	    <div id="a-subnav-bottom" class="a-subnav-bottom"></div>
	  </div>
	<?php end_slot() ?>
  
  <?php include_partial('aBlogAdmin/flashes') ?>
	

	<div id="a-admin-content" style="float:left;width:800px" class="main">
	
  <?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>
  
  <div id="a-admin-right-subnav" class="right-subnav">

		<?php echo form_tag_for($form, '@a_blog_admin', array('id'=>'a-admin-form')) ?>  

		<div class="a-blog-post-title">
			<h2>Title</h2>
			<?php echo $form['title']->render() ?>
			<?php echo $form['title']->renderError() ?>
		</div>

    <?php include_partial('aBlogAdmin/optionsForm', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
  </div>
  
  <div id="a-admin-footer">
    <?php include_partial('aBlogAdmin/form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php include_partial('aBlogAdmin/form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
