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
	  dataType:'json',
	  data:jQuery('#a-admin-form').serialize(),
	  success:function(data, textStatus)
		{
      //data is a JSON object, we can handle any updates with it
      updateTitleAndSlug(data.title, data.slug);
			updateComments(data.allow_comments);
	 	},
	 	url: slug_url
	});
}

// Update Title Function for Ajax calls when it is returned clean from Apostrophe
function updateTitle(title)
{
		var titleInput = $('#a_blog_post_title_interface');
		
		if (title != null) 
		{
			titleInput.val(title);			
		};
		
}

// Update Slug Function for Ajax calls when it is returned clean from Apostrophe
function updateSlug(slug)
{
		var permalinkInput = $('#a_blog_post_permalink_interface');
		
		if (slug != null)
		{
			permalinkInput.val(slug);			
		};
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

function updateComments(enabled)
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