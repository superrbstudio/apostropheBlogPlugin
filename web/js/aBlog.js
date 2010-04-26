// Publish / Unpublish Button
function checkAndSetPublish(status, slug_url)
{
	//todo: use jq to get the action from the Form ID
	
	var postStatus = $('#a_blog_post_status');
	var publishButton = $('#a-admin-form a.a-publish-post');

	if (status == 'published') {
		publishButton.addClass('published');
	};

	publishButton.unbind('click').click(function(){

		$(this).blur();

		if (status == 'draft') {
			postStatus.val('published');
			publishButton.addClass('published');			
			sendUserMessage('','Your post is now published.');			
		}
		else
		{
			postStatus.val('draft');
			publishButton.removeClass('published');			
			sendUserMessage('','You unpublished your post.');			
		};
		
		// If slug_url
		if (typeof slug_url != 'undefined') {
			updateBlogForm(slug_url);			
		};

	});			
}

// Ajax Update Blog Form
function updateBlogForm(slug_url, event)
{

	$('#a-blog-post-status-indicator').ajaxStart(function(){
		$(this).show();
	})
	$('#a-blog-post-status-indicator').ajaxStop(function(){
		$(this).hide();
	})

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
			
			// TODO: There needs to be a way to selectively call these functions
			// Comments are getting updated EVERYTIME anything gets updated and it's not necessary
			// We need to provide a scope or context to the ajax event

			// if ( the Template has changed ) {
			// updateTemplate(data.template, data.feedback);
			// };

			// if ( the Comments has changed ) {
      updateComments(data.aBlogPost.allow_comments); // Update Comments after ajax
			// };

			checkAndSetPublish(data.aBlogPost.status, slug_url); // Re-set Publish button after ajax
      updateTitleAndSlug(data.aBlogPost.title, data.aBlogPost.slug); // Update Title and Slug after ajax
			
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
    var slugInput = $('#a_blog_post_slug');
		if (slug != null)
		{
			permalinkInput.val(slug);
      slugInput.val(slug);
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
		sendUserMessage('Comments Enabled','Viewers can leave comments on this blog post.');
	}
	else
	{
		$('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');		
		sendUserMessage('Disabled Comments','Viewers of this blog post cannot comment on it.');
	}
}

function updateTemplate(template, feedback)
{
	location.reload(true);
	// sendUserMessage(feedback); // See sendUserMessage function below 
}

// Send a message to the blog editor confirming a change made via Ajax
function sendUserMessage(label, desc)
{	
	// Messages are turned off for now!
	
	// var mLabel = (label)?label.toString():""; // passed from ajaxAction
	// var mDescription = (desc)?desc.toString(): ""; // passed from ajaxAction
	// var newMessage = "<dt>"+mLabel+"</dt><dd>"+mDescription+"</dd>";
	// var messageContainer = $('#a-blog-post-status-messages');
	// messageContainer.append(newMessage).addClass('has-messages');
	// messageContainer.children('dt:last').fadeTo(5000,1).fadeOut('slow', function(){ $(this).remove(); }); // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	// messageContainer.children('dd:last').fadeTo(5000,1).fadeOut('slow', function(){	$(this).remove(); checkMessageContainer(); });  // This uses ghetto fadeTo delay because jQ1.4 has built-in delay
	// 
	// function checkMessageContainer()
	// {
	// 	if (!messageContainer.children().length) {
	// 		messageContainer.removeClass('has-messages');
	// 	};
	// }
}