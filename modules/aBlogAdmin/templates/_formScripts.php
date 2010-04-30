<script type="text/javascript" charset="utf-8">
	
	function updateBlogMulti() { updateBlogForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>'); }

	$(document).ready(function(){
		
	    $('#a-admin-form').change(function(event) {
		    if (!( event.target.className == 'a-multiple-select-input' && event.target.options[0].selected == true || event.target.name == 'add-text' ))
				{
		      updateBlogForm('<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>', event);
				}
	    });

			// Sidebar Toggle
			// =============================================
	    $('.a-sidebar-toggle').click(function(){
	      $(this).toggleClass('open').next().toggle();
	    })

			// Comments Toggle
			// =============================================
			$('.section.comments a.allow_comments_toggle').click(function(event){
				event.preventDefault();
				toggleCheckbox($('#a_blog_post_allow_comments'));
				updateBlogForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			});

			initTitle('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			initPermalink('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    checkAndSetPublish('<?php echo $a_blog_post->status  ?>','<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    aMultipleSelect('#categories-section', { 'choose-one': '<?php echo __('Choose Categories', array(), 'apostrophe_blog') ?>', 'add': '<?php echo __('+ New Category', array(), 'apostrophe_blog') ?>', 'onChange': updateBlogMulti });
	    aMultipleSelect('#editors-section', { 'choose-one': '<?php echo __('Choose Editors', array(), 'apostrophe_blog') ?>','onChange': updateBlogMulti  });
    
	 });

</script>