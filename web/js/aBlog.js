// Publish / Unpublish Button
function checkAndSetPublish(slug_url)
{
	var postStatus = $('#a_blog_post_status');
	var publishButton = $('#a-admin-form a.a-publish-post');

	if (postStatus.val() == 'published') {
		publishButton.addClass('published');
	};

	publishButton.unbind('click').click(function(){

		//$(this).toggleClass('published');
		$(this).blur();

		if (postStatus.val() == 'draft') {
			postStatus.val('published');
		}
		else
		{
			postStatus.val('draft');
		};
		updateBlogForm(slug_url);
	});			
}

// Ajax Update Blog Form
function updateBlogForm(slug_url, event)
{
	$.ajax({
	  type:'POST',
	  dataType:'text',
	  data:jQuery('#a-admin-form').serialize(),
	  complete:function(xhr, textStatus)
		{
      if(textStatus == 'success')
      {
      //data is a JSON object, we can handle any updates with it
      var json = xhr.getResponseHeader('X-Json');
      var data = eval('(' + json + ')');

      updateTitleAndSlug(data.aBlogPost.title, data.aBlogPost.slug);
      updateComments(data.aBlogPost.allow_comments);

			// if ( the Template has changed ) {
			// updateTemplate(data.template, data.feedback);
			// };
			aUI('#a-admin-form');
      }
	 	},
	 	url: slug_url
	});
}

// Update Title Function for Ajax calls when it is returned clean from Apostrophe
function updateTitle(title, feedback)
{
		var titleInput = $('#a_blog_post_title_interface');
		
		if (title != null) 
		{
			titleInput.val(title);			
		};
		
		// sendUserMessage(feedback); // See sendUserMessage function below
}

// Update Slug Function for Ajax calls when it is returned clean from Apostrophe
function updateSlug(slug, feedback)
{
		var permalinkInput = $('#a_blog_post_permalink_interface');
		
		if (slug != null)
		{
			permalinkInput.val(slug);			
		};

		// sendUserMessage(feedback); // See sendUserMessage function below
}

// Update TitleAndSlug Function to save u time :D !
function updateTitleAndSlug(title, slug)
{
	updateTitle(title);
	updateSlug(slug);
}

// Toggle any checkbox you want with this one
function toggleCheckbox(checkbox)
{
	checkbox.attr('checked', !checkbox.attr('checked')); 
}

function updateComments(enabled, feedback)
{
	if (enabled)
	{
		$('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
		// sendUserMessage(feedback); // See sendUserMessage function below		
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
		// sendUserMessage(feedback); // See sendUserMessage function below		
	}
}

function updateTemplate(template, feedback)
{
	location.reload(true);
	// sendUserMessage(feedback); // See sendUserMessage function below 
}

function sendUserMessage(params)
{
	// This will be used to send messages up to the top of the page telling the user what's happening
	// Dan we need to set up a stored location for messages to be delivered to the user after an event
	
	// For Example:
	// User changes template, this event happens and a message gets passed to this function
	// That message is canned somewhere inside PHP inside the plugin where I18N can get to it
}