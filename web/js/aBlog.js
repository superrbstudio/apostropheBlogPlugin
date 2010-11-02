function aBlogEnableTitle()
{
	apostrophe.formUpdates({ 'selector': '#a-blog-item-title-interface', 'update': 'a-blog-title-and-slug' }); 

	// Form has class, input element has id
	var titleInterface = $('#a-blog-item-title-interface');
	var tControls = titleInterface.find('ul.a-controls');
	var tInput = titleInterface.find('.a-title');
	var originalTitle = tInput.val();
	tInput.keyup(function(event) {
		if (tInput.val().trim() != originalTitle.trim())
		{
			titleInterface.addClass('has-changes');
			tControls.fadeIn();
		}
		return false;
	});
	titleInterface.find('.a-cancel').click(function() {
		tInput.val(originalTitle);
		tControls.hide();
		return false;
	});
}

function aBlogEnableSlug()
{
	apostrophe.formUpdates({ 'selector': '#a-blog-item-permalink-interface', 'update': 'a-blog-title-and-slug' }); 
	
	// Form has class, input element has id
	var slugInterface = $('#a-blog-item-permalink-interface');
	var tControls = slugInterface.find('ul.a-controls');
	var tInput = slugInterface.find('.a-slug');
	var originalSlug = tInput.val();
	tInput.keyup(function(event) {
		if (tInput.val().trim() != originalSlug.trim())
		{
			slugInterface.addClass('has-changes');
			tControls.fadeIn();
		}
		return false;
	});
	slugInterface.find('.a-cancel').click(function() {
		tInput.val(originalSlug);
		tControls.hide();
		return false;
	});
}

function aBlogUpdateComments(enabled, feedback)
{
	if (enabled)
	{
		$('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
	}
}

function aBlogEnableNewForm()
{
	var newForm = $('.a-blog-admin-new-form');
	newForm.submit(function() {
		var form = $(this);
		$.post(form.attr('action'), $(this).serialize(), function(data) {
			form.parents('.a-blog-admin-new-ajax').html(data);	
		});
		return false;
	});
}

function aBlogEnableForm(options)
{
	var changed = false;
	var savedState = null;
	var form = $('#a-admin-form');
	apostrophe.formUpdates({ selector: '#a-admin-form', update: 'a-admin-form' });
	// Due to the way our markup is structured this is a better place for the little
	// 'updating' message
	$('.a-subnav-wrapper').addClass('a-ajax-attach-updating');
	var status = form.find('[name=a_blog_item[publication]]');
	var init = true;
	
	// A convenience within this closure to keep us from getting lazy and
	// using selectors that aren't specific to the form
	function find(sel)
	{
		return form.find(sel);
	}
	
	status.change(function() {
		var c = form.find('.a-published-at-container');
		var s = status.val();
		if (s === 'schedule') 
		{
			c.show();
		}
		else
		{
			c.hide();
		}
		if (!init)
		{
			find('.a-save-blog-main').html(options['update-labels'][s]);
		}
		init = false;
	});
	status.change();

	find('.template.section select').change(function() {
		alert(options['template-change-warning']);
		// Let the form submit as a full refresh
		$(form).unbind('submit.aFormUpdates');
	});
	
	find('.post-editors-toggle').click(function() {
		find('.post-editors-options').show();
		find('.post-editors-toggle').hide();
		return false;
	});
	
	var p = { 'choose-one': options['editors-choose-label'] };
	aMultipleSelect('#editors-section', p);
	p = { 'choose-one': options['categories-choose-label'] };
	if (options['categories-add'])
	{
		p['add'] = options['categories-add-label'];
	}
	aMultipleSelect(form.find('#categories-section'), p);
	
	function toggleAllDay(checkbox) {
		$(checkbox).toggleClass('all_day_enabled');
		find('.start_time').toggleClass('time_disabled').toggle();
		find('.end_date').toggleClass('time_disabled').toggle();
	}
	
	find('.all_day input[type=checkbox]').bind('click', function() {
		toggleAllDay($(this));
	});
	
	if (find('.all_day input[type=checkbox]:checked').length)
	{
		toggleAllDay($(this));
	}
}

function aBlogGetPostStatus()
{
	var postStatus = $('#a_blog_item_status');
	return postStatus.val();
}
