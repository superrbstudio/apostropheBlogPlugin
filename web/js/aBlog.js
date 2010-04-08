// Publish / Unpublish Button
function checkAndSetPublish(slug_url)
{
	var postStatus = $('#a_blog_post_status');

	if (postStatus.val() == 'published') {
		$('#a-admin-form a.a-publish-post').addClass('published');
	};

	$('#a-admin-form a.a-publish-post').unbind('click').click(function(){

		$(this).toggleClass('published');
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
function updateBlogForm(slug_url)
{
	$.ajax({
	  type:'POST',
	  dataType:'html',
	  data:jQuery('#a-admin-form').serialize(),
	  success:function(data, textStatus)
		{
			$('#a-admin-blog-post-form').html(data);
			aUI('#a-admin-form');
	 	},
	 	url: slug_url
	});
}