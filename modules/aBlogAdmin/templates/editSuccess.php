<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('aBlogAdmin/assets') ?>
<?php slot('body_class') ?>a-admin <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?> <?php echo $a_blog_post['template'] ?><?php end_slot() ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
  <?php // include_partial('aBlogAdmin/form_bar', array('title' => __('Edit Blog Post', array(), 'apostrophe-blog'))) ?>

	<?php slot('a-subnav') ?>
		<div class="a-subnav-wrapper blog">
			<div class="a-subnav-inner">	
				<ul class="a-admin-action-controls">
					<li><a href="<?php echo url_for('@a_blog_admin'); ?>" class="all-posts-btn"><?php echo __('All Posts', array(), 'apostrophe-blog') ?></a></li>
	         <?php include_partial('aBlogAdmin/list_actions', array('helper' => $helper)) ?>
				</ul>
				<div id="a-blog-post-status-indicator"></div>
			</div> 
	  </div>
	<?php end_slot() ?>
  
  <?php include_partial('aBlogAdmin/flashes') ?>
	
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
    <?php include_partial('aBlogAdmin/form', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    </div>
  </div>
  
  <div class="a-admin-footer">
    <?php include_partial('aBlogAdmin/form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('aBlogAdmin/form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>


<script type="text/javascript" charset="utf-8">
	function updateBlog(event)
  {
    if(event.target.name == 'select-<?php echo $form['categories_list']->renderName() ?>[]' &&
       event.target.options[0].selected == true)
    {

    }
    else if(event.target.name == 'add-text') {}
    else
    {
      updateBlogForm('<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>', event);
    }
  }

  $(document).ready(function(){
    
		// Title Interface 
		// =============================================
		var titleInterface = $('#a_blog_post_title_interface');
		var titlePlaceholder = $('#a-blog-post-title-placeholder');
		var originalTitle = "<?php echo $a_blog_post->title ?>";

		<?php if ($a_blog_post->title == 'untitled'): ?>
		titleInterface.focus(); // The blog post is 'Untitled' -- Focus the input
		<?php endif ?>
		
		// Title: On Change Compare
		titleInterface.change(function(){
			if ($(this).val() != '') { // If the input is not empty
				$('#a_blog_post_title').val($(this).val()); // Pass the value to the admin form and update
				updateBlogForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			};
		});
		
		titleInterface.blur(function(){
			// Check for Empty Title Field			
			if ($(this).val() == '') 
			{ 	
				$(this).next().show(); 
			}
		});

		titleInterface.focus(function(){
			// Always hide the placeholder on focus
			$(this).next().hide(); 
		});
			
		titlePlaceholder.mousedown(function(){
			// If you click the placeholder text 
			// focus the input (Mousedown is faster than click here)
			titleInterface.focus(); 
		}).hide();
		
		// Permalink Interface  
		// =============================================
		var permalinkInterface = $('#a-blog-post-permalink-interface');
		var pInput = permalinkInterface.find('input');
		var pControls = permalinkInterface.find('ul.a-controls');
		var originalSlug = '<?php echo $a_blog_post->slug ?>';
	
		// Permalink: On Focus Listen for Changes
		pInput.focus(function(){
			$(this).select();
			$(this).keyup(function(){
				if ($(this).val().trim() != originalSlug)
				{
					permalinkInterface.addClass('has-changes');
					pControls.fadeIn();
				}
			});
		});

		// Permalink Interface Controls: Save | Cancel
		// =============================================
		pControls.click(function(event){
			event.preventDefault();
			$target = $(event.target);
						
			if ($target.hasClass('a-save'))
			{
				if (pInput.val() == '') { 	
					pInput.val(originalSlug);
					pControls.hide();
				}
				if ((pInput.val() != '') && (pInput.val().trim() != originalSlug)) {
					$('#a_blog_post_slug').val(pInput.val()); // Pass the value to the admin form and update
					updateBlogForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
				}										
			}
			
			if ($target.hasClass('a-cancel'))
			{
				pInput.val(originalSlug);
				pControls.hide();
			}
		});		

		// Comments Toggle
		// =============================================
		$('.section.comments a.allow_comments_toggle').click(function(event){
			event.preventDefault();
			toggleCheckbox($('#a_blog_post_allow_comments'));
			updateBlogForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
		});

  });
</script>