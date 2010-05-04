<script type="text/javascript" charset="utf-8">
	
	function updateBlogMulti() { aBlogUpdateForm('<?php echo url_for('a_event_admin_update',$a_event) ?>'); }

	$(document).ready(function(){
		
	    $('#a-admin-form').change(function(event) {
		    if (!( event.target.className == 'a-multiple-select-input' && event.target.options[0].selected == true || event.target.name == 'add-text' ))
				{
		      aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>', event);
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
				aBlogUpdateForm('<?php echo url_for('a_event_admin_update',$a_event) ?>');
			});

			aBlogTitle('<?php echo url_for('a_event_admin_update',$a_event) ?>');
			aBlogPermalink('<?php echo url_for('a_event_admin_update',$a_event) ?>');
	    aBlogPublishBtn('<?php echo $a_event->status  ?>','<?php echo url_for('a_event_admin_update',$a_event) ?>');
	    aMultipleSelect('#categories-section', { 'choose-one': '<?php echo __('Choose Categories', array(), 'apostrophe_blog') ?>', 'add': '<?php echo __('+ New Category', array(), 'apostrophe_blog') ?>', 'onChange': updateBlogMulti });
	    aMultipleSelect('#editors-section', { 'choose-one': '<?php echo __('Choose Editors', array(), 'apostrophe_blog') ?>','onChange': updateBlogMulti  });
    
	 });

</script>