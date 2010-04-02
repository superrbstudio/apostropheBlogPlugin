<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('aBlogAdmin/assets') ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
  <?php include_partial('aBlogAdmin/form_bar', array('title' => __('Edit Blog Post', array(), 'messages'))) ?>

	<?php slot('a-subnav') ?>
  <div class="a-subnav-wrapper blog">
  	<div class="a-subnav-inner">	
       <ul class="a-admin-action-controls">
         <li><?php echo link_to('New Post', '@a_blog_admin_new', array('class' => 'a-btn icon a-add')) ?></li>
         <li><?php echo link_to('Edit Categories', '@a_blog_category_admin') ?></li>
         <li><?php echo link_to('Edit Posts', '@a_blog_admin') ?></li>
         <li><?php echo link_to('Edit Comments', '@a_comment_admin') ?></li>
       </ul>
     </div> 
  </div>
	<?php end_slot() ?>
  
  <?php include_partial('aBlogAdmin/flashes') ?>
	

	<div class="a-admin-content main">	
  	<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>
  </div>

  <div class="a-admin-sidebar">
    <div id='a-admin-blog-post-form'>
    <?php include_partial('aBlogAdmin/form', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    </div>
  </div>
  
  <div class="a-admin-footer">
    <?php include_partial('aBlogAdmin/form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('aBlogAdmin/form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>
