<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('assets') ?>
<?php slot('body_class') ?>a-admin a-blog-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_blog_post['template'] ?><?php end_slot() ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
  <?php // include_partial('aBlogAdmin/form_bar', array('title' => __('Edit Blog Post', array(), 'apostrophe-blog'))) ?>

	<?php slot('a-subnav') ?>
		<div class="a-subnav-wrapper blog">
			<div class="a-subnav-inner">	
				<ul class="a-admin-action-controls">
					<li><a href="<?php echo url_for('@a_blog_admin'); ?>" class="all-posts-btn"><?php echo __('All Posts', array(), 'apostrophe-blog') ?></a></li>
	         <?php include_partial('list_actions', array('helper' => $helper)) ?>
				</ul>
				<div id="a-blog-post-status-indicator"></div>
			</div> 
	  </div>
	<?php end_slot() ?>
  
  <?php include_partial('flashes') ?>
	
	<div class="a-admin-content main">	
		
		<?php if (0): ?>	
		<?php // We aren't using status messages right now ?>
			<dl id="a-blog-post-status-messages"></dl>
		<?php endif ?>
		
		<div id="a-blog-post-title-interface" class="a-blog-post-title-interface">
			<input type="text" id="a_blog_post_title_interface" value="<?php echo ($a_blog_post->title == 'untitled')? '':$a_blog_post->title ?>" />
			<div id="a-blog-post-title-placeholder"><?php echo __('Title your post...', array(), 'apostrophe-blog') ?></div>
		</div>		

		<div id="a-blog-post-permalink-interface">
			<h6>Permalink:</h6> 
			<div class="a-blog-post-permalink-wrapper url">
        <span><?php echo aTools::urlForPage($a_blog_post->findBestEngine()->getSlug()).'/' ?></span><?php // Dan, Can you echo the REAL URL prefix here -- I don't know how to build a URL based on the complex blog route business we are doing  ?>
			</div>
			<div class="a-blog-post-permalink-wrapper slug">
				<input type="text" name="a_blog_post_permalink_interface" value="<?php echo $a_blog_post->slug ?>" id="a_blog_post_permalink_interface">
			  <ul class="a-controls slug">
			    <li><a href="#" class="a-btn a-save mini"><?php echo __('Save', array(), 'apostrophe_blog') ?></a></li>
			    <li><a href="#" class="a-btn a-cancel no-label mini"><?php echo __('Cancel', array(), 'apostrophe_blog') ?></a></li>
			  </ul>				
			</div>
		</div>

  	<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>

  </div>

  <div class="a-admin-sidebar">
    <div id='a-admin-blog-post-form'>
    <?php include_partial('form', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    </div>
  </div>
  
  <div class="a-admin-footer">
    <?php include_partial('form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>