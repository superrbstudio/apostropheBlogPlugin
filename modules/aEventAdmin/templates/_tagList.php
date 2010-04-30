<div class="a-admin-form-field-tags">
  <h5>Popular Tags</h5>

  <div id="blog-tag-list">
    <?php $n=1; foreach ($tags as $tag => $count): ?>
    		<?php echo link_to_function($tag, '', array('class' => (in_array($tag, $a_event->getTags())) ? 'selected recommended-tag' : 'recommended-tag', )) ?><?php echo ($n < count($tags)) ? ', ' : ''; ?>			  
    <?php $n++; endforeach ?>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		var tagList = $('#a_event_tags');
			
		$('.recommended-tag').each(function(){
		
			$(this).click(function(){

				var theTag = $(this).text();
				var tagSeparator = ", ";
				var currentTags = tagList.val();			
			
					if (!$(this).hasClass('selected'))
					{ 
						if (currentTags == "") { tagSeparator = ""; }; // Remove separator if there are no starting tags
						tagList.val(currentTags += tagSeparator+theTag); 
					}
					else
					{
						newTagList = currentTags.split(',');
						tagPosition = $.inArray(" "+theTag, newTagList);

						if (tagPosition == -1)
						{ // If it can't find the tag in the array, it is the first tag in the list
							tagPosition = 0;
						}

						newTagList.splice(tagPosition,1);
						tagList.val(newTagList.toString());
					}

					$(this).toggleClass('selected');
			
				});	
		});
	});
</script>