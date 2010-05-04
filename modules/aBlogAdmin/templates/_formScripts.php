<script type="text/javascript" charset="utf-8">
	
	function updateBlogMulti() { aBlogUpdateForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>'); }

	$(document).ready(function(){
		
	    $('#a-admin-form').change(function(event) {
		    if (!( event.target.className == 'a-multiple-select-input' && event.target.options[0].selected == true || event.target.name == 'add-text' ))
				{
		      aBlogUpdateForm('<?php echo url_for('a_blog_admin_update', $a_blog_post) ?>', event);
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
				aBlogCheckboxToggle($('#a_blog_item_allow_comments'));
				aBlogUpdateForm('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			});

			aBlogTitle('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
			aBlogPermalink('<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    aBlogPublishBtn('<?php echo $a_blog_post->status  ?>','<?php echo url_for('a_blog_admin_update',$a_blog_post) ?>');
	    aMultipleSelect('#categories-section', { 'choose-one': '<?php echo __('Choose Categories', array(), 'apostrophe_blog') ?>', 'add': '<?php echo __('+ New Category', array(), 'apostrophe_blog') ?>', 'onChange': updateBlogMulti });
	    aMultipleSelect('#editors-section', { 'choose-one': '<?php echo __('Choose Editors', array(), 'apostrophe_blog') ?>','onChange': updateBlogMulti  });
    
	 });

</script>