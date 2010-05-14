function aBlogPublishBtn(status, slug_url)
{
	//todo: use jq to get the action from the Form ID	for the slug_url
	var postStatus = $('#a_blog_item_status');
	var publishButton = $('#a-blog-publish-button');

	if (status == 'published') {
		publishButton.addClass('published');
	};

	publishButton.unbind('click').click(function(){
		$(this).blur();

		if (status == 'draft') 
		{
			postStatus.val('published');
			publishButton.addClass('published');			
		}
		else
		{
			postStatus.val('draft');
			publishButton.removeClass('published');			
		};
		
		// If slug_url
		if (typeof slug_url != 'undefined') 
		{
			aBlogUpdateForm(slug_url);			
		};
	});			
}

function aBlogTitle(slug_url)
{
	// Title Interface 
	// =============================================
	var titleInterface = $('#a_blog_item_title_interface');
	var titlePlaceholder = $('#a-blog-item-title-placeholder');
	var originalTitle = titleInterface.val();

	if (originalTitle == 'untitled' || originalTitle == '') 
	{ // The blog post has no title -- Focus the input		
		titleInterface.focus(); 
	};
	
	// Title: On Change Compare
	titleInterface.change(function(){
		if ($(this).val() != '') 
		{ // If the input is not empty
			// Pass the value to the admin form and update
			$('#a_blog_item_title').val($(this).val());
			aBlogUpdateForm(slug_url);
		};
	});
	
	titleInterface.blur(function()
	{ // Check for Empty Title Field			
		if ($(this).val() == '') 
		{ 	
			$(this).next().show(); 
		}
	});

	titleInterface.focus(function()
	{	// Always hide the placeholder on focus
		$(this).next().hide(); 
	});
		
	titlePlaceholder.mousedown(function()
	{	// If you click the placeholder text 
		// focus the input (Mousedown is faster than click here)
		titleInterface.focus(); 
	}).hide();
}

function aBlogPermalink(slug_url)
{
	// Permalink Interface  
	// =============================================
	var permalinkInterface = $('#a-blog-item-permalink-interface');
	var pInput = permalinkInterface.find('input');
	var pControls = permalinkInterface.find('ul.a-controls');
	var originalSlug = pInput.val();

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
	pControls.click(function(event)
	{
		event.preventDefault();
		$target = $(event.target);
					
		if ($target.hasClass('a-save'))
		{
			if (pInput.val() == '') 
			{ 	
				pInput.val(originalSlug);
				pControls.hide();
			}
			if ((pInput.val() != '') && (pInput.val().trim() != originalSlug)) 
			{
				$('#a_blog_item_slug').val(pInput.val()); // Pass the value to the admin form and update
					aBlogUpdateForm(slug_url);				
			}										
		}
		
		if ($target.hasClass('a-cancel'))
		{
			pInput.val(originalSlug);
			pControls.hide();
		}
	});
}


// Ajax Update Blog Form
function aBlogUpdateForm(slug_url, event)
{
	$.ajax({
	  type:'POST',
	  dataType:'text',
	  data:jQuery('#a-admin-form').serialize(),
	  complete:function(xhr, textStatus)
		{			
      if(textStatus == 'success')
      {
      var json = xhr.getResponseHeader('X-Json'); //data is a JSON object, we can handle any updates with it
      var data = eval('(' + json + ')');
			
      if ( typeof(data.modified.template) != "undefined" ) {
        aBlogUpdateTemplate(data.template, data.feedback);
      };

      if ( typeof(data.modified.allow_comments) != "undefined" ) {
      	aBlogUpdateComments(data.aBlogPost.allow_comments); // Update Comments after ajax
			};

			aBlogPublishBtn(data.aBlogPost.status, slug_url); // Re-set Publish button after ajax
      aBlogUpdateTitleAndSlug(data.aBlogPost.title, data.aBlogPost.slug); // Update Title and Slug after ajax
			aBlogUpdateMessage('Saved!', data.aBlogPost.updated_at);
			aUI('#a-admin-form');
      }
	 	},
	 	url: slug_url
	});
}


function aBlogUpdateTitle(title)
{ // Update Title Function for Ajax calls when it is returned clean from Apostrophe
	var titleInput = $('#a_blog_item_title_interface');
		
	if (title != null) 
	{
		titleInput.val(title);			
	};
}


function aBlogUpdateSlug(slug)
{ // Update Slug Function for Ajax calls when it is returned clean from Apostrophe
	var permalinkInput = $('#a_blog_item_permalink_interface');
  var slugInput = $('#a_blog_item_slug');

	if (slug != null)
	{
		permalinkInput.val(slug);
     slugInput.val(slug);
	};
}


function aBlogUpdateTitleAndSlug(title, slug)
{ // Update TitleAndSlug Function to save u time :D !
	aBlogUpdateTitle(title);
	aBlogUpdateSlug(slug);
}

function aBlogCheckboxToggle(checkbox)
{ // Toggle any checkbox you want with this one
	checkbox.attr('checked', !checkbox.attr('checked')); 
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

function aBlogUpdateTemplate(template, feedback)
{
	location.reload(true);
}

function aBlogUpdateMessage(msg, timestamp)
{
	if (typeof msg == 'undefined') {
		msg = 'Saved!';
	};

	var publishButton = $('#a-blog-publish-button');
	var pUpdate = $('#a-blog-item-update');
	var lastSaved = $('#post-last-saved');

	if (pUpdate.data('animating') != 1) {
		pUpdate.data('animating',1).text(msg).fadeIn(100, function(){
			publishButton.children().hide();
			pUpdate.fadeTo(500,1, function(){
				pUpdate.fadeOut(500, function(){
					if (publishButton.hasClass('published')) 
					{
						publishButton.children('.unpublish').fadeIn(100);
					}
					else	
					{
						publishButton.children('.publish').fadeIn(100);					
					}
					lastSaved.find('span').text(timestamp);
					lastSaved.fadeIn(2000, function(){
						lastSaved.fadeTo(3000, 1, function(){
							// lastSaved.fadeOut(); // Fade Out Message after some time
						});
					});					
					pUpdate.data('animating', 0);
				});
			});
		});
	};	
}

function aBlogSendMessage(label, desc)
{	
	// Messages are turned off for now!
	// Send a message to the blog editor confirming a change made via Ajax	
	var mLabel = (label)?label.toString():""; // passed from ajaxAction
	var mDescription = (desc)?desc.toString(): ""; // passed from ajaxAction
	var newMessage = "<dt>"+mLabel+"</dt><dd>"+mDescription+"</dd>";
	var messageContainer = $('#a-blog-item-status-messages');
	messageContainer.append(newMessage).addClass('has-messages');
	messageContainer.children('dt:last').fadeTo(5000,1).fadeOut('slow', function(){ $(this).remove(); }); // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	messageContainer.children('dd:last').fadeTo(5000,1).fadeOut('slow', function(){	$(this).remove(); checkMessageContainer(); });  // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	
	function checkMessageContainer()
	{
		if (!messageContainer.children().length) {
			messageContainer.removeClass('has-messages');
		};
	}
}

function aBlogSetDateRange(a) 
{  
	var b = new Date();  
	var c = new Date(b.getFullYear(), b.getMonth(), b.getDate());  
	if (a.id == 'a_blog_item_end_date_jquery_control') {  
	    if ($('#a_blog_item_start_date_jquery_control').datepicker('getDate') != null) {  
	        c = $('#a_blog_item_start_date_jquery_control').datepicker('getDate');  
	    }
	  	$('#a_blog_item_end_date_jquery_control').datepicker('setDate', c);
	}  
	return {  
		 minDate: c  
	}  	
}