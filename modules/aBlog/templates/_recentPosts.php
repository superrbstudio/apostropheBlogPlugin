<ul class="a-blog-recentposts">
	<?php foreach ($a_blog_posts as $a_blog_post): ?>
		<li>
			<div class="a-blog-post <?php echo count($a_blog_post->getAttachedMedia()) > 0? 'contains-media' : ''?> ">
			<h3 class="a-blog-post-title"><?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?></h3>
			<?php if ($a_blog_post->getAttachedMedia()): ?>
				<?php if (in_array('aSlideshowSlot', sfConfig::get('sf_enabled_modules'))): ?>
					<div class="a-blog-post-media">
						<?php include_component('aSlideshowSlot', 'slideshow', array(
							'items' => $a_blog_post->getAttachedMedia(),
							'id' => $a_blog_post->getId(),
							'options' => array(
								'width' => isset($options['aSlideshow']['width'])? $options['aSlideshow']['width'] : 290, 
								'height' => isset($options['aSlideshow']['height'])? $options['aSlideshow']['height'] : 210, 
								'resizeType' => isset($options['aSlideshow']['resizeType'])? $options['aSlideshow']['resizeType'] : 'c',
								'flexheight' => isset($options['aSlideshow']['flexheight'])? $options['aSlideshow']['flexheight'] : false, 
								'arrows' => isset($options['aSlideshow']['arrows'])? $options['aSlideshow']['arrows'] : false )
						)) ?>
					</div>
				<?php endif ?>
			<?php endif ?>
			<div class="a-blog-post-excerpt-container">
				<?php echo ($a_blog_post->getExcerpt()) ? $a_blog_post->getExcerpt() : $a_blog_post->getPreview(30) ?>
				<div class="a-blog-read-more"><?php echo link_to('Read More', 'a_blog_post', $a_blog_post, array('class' => 'a-blog-more')) ?></div>
			</div>
			</div>
		</li>
	<?php endforeach ?>
</ul>