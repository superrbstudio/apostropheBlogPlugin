<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>
<script type="text/javascript" charset="utf-8">	
	function aBlogUpdateMulti() { aBlogUpdateForm('<?php echo url_for('a_event_admin_update',$a_event) ?>'); }
	$(document).ready(function(){
	
			// Save functions
			// ==============================================
			var changed = true;
			
			$('#a-admin-form').change(function(event) {
				changed = true;
			});
			
			// Time interval
			setInterval(function() {
				if (changed) {
					aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>');
					changed = false;
				}
			}, 30000);

			// Save button
			//$('#a-event-save-button').bind('click', function(event) {
			//		aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>');
			//		return false;
			//});
			
			// Save Links
			$('.a-save-event').bind('click', function(event) {
				aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>');
				return false;
			});

			// Date change
      $('#<?php echo $form['start_date']->renderId() ?>-ui').bind('aTimeUpdated',function(event){
        aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>', event);
      });

      $('#<?php echo $form['end_date']->renderId() ?>-ui').bind('aTimeUpdated',function(event){
        aBlogUpdateForm('<?php echo url_for('a_event_admin_update', $a_event) ?>', event);
      });

			
			// All Day Toggle
			// =============================================
			function toggleAllDay(checkbox) {
				$(checkbox).toggleClass('all_day_enabled');
				$('.start_time').toggleClass('time_disabled').toggle();
				$('.end_date').toggleClass('time_disabled').toggle();
			}
			
			$('#<?php echo $form['all_day']->renderId() ?>').bind('click', function() {
				toggleAllDay($(this));
			});
			
			if ($('#<?php echo $form['all_day']->renderId() ?>').is(':checked'))
			{
				toggleAllDay($('#<?php echo $form['all_day']->renderId() ?>'));
			}
			


			// Sidebar Toggle
			// =============================================
	    $('.a-sidebar-toggle').click(function(){
	      $(this).toggleClass('open').next().toggle();
	    });

			$('.post-date-toggle').click(function(){
	      $(this).toggleClass('open').closest('.post-published').children('.option').toggle();
				$('.post-published-sentence-toggle').toggle();
			});
			
			$('.post-date-published-at-cancel').click(function() {
				$(this).closest('.post-published').children('.option').toggle();
				$('.post-published-sentence-toggle').toggleClass('open').toggle();
			});

			// Comments Toggle
			// =============================================
			$('.section.comments a.allow_comments_toggle').click(function(event){
				event.preventDefault();
				aBlogCheckboxToggle($('#a_blog_item_allow_comments'));
				aBlogUpdateForm('<?php echo url_for('a_event_admin_update',$a_event) ?>');
			});

			aPopularTags($('#a_blog_item_tags'), $('#blog-tag-list .recommended-tag'));
			aBlogItemTitle('<?php echo url_for('a_event_admin_update',$a_event) ?>');
			aBlogItemPermalink('<?php echo url_for('a_event_admin_update',$a_event) ?>');
	    aBlogPublishBtn('<?php echo $a_event->status  ?>','<?php echo url_for('a_event_admin_update',$a_event) ?>');
	    aMultipleSelect('#categories-section', { 'choose-one': '<?php echo __('Choose Categories', array(), 'apostrophe') ?>' <?php if($sf_user->hasCredential('admin')): ?>, 'add': '<?php echo __('+ New Category', array(), 'apostrophe') ?>'<?php endif ?>, 'onChange': aBlogUpdateMulti });
	    aMultipleSelect('#editors-section', { 'choose-one': '<?php echo __('Choose Editors', array(), 'apostrophe') ?>','onChange': aBlogUpdateMulti  });
	
	 });
	$(window).bind('beforeunload', function() {
	<?php // We want to save the blog post editor when you close the browser window or navigate away from it ?>
		aBlogUpdateForm('<?php echo url_for('a_event_admin_update',$a_event) ?>');
	});	
</script>