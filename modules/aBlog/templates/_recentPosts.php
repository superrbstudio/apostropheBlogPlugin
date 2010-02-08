<ul class="a-blog-recentposts">
	<?php foreach ($a_blog_posts as $a_blog_post): ?>
		<li>
			<div class="a-blog-post">
			<h3 class="a-blog-post-title"><?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?></h3>
			<div class="a-blog-post-excerpt-container">
				<?php echo ($a_blog_post->getExcerpt()) ? $a_blog_post->getExcerpt() : $a_blog_post->getPreview(30) ?>
				<div class="a-blog-read-more"><?php echo link_to('Read More', 'a_blog_post', $a_blog_post, array('class' => 'a-blog-more')) ?></div>
			</div>
			</div>
		</li>
	<?php endforeach ?>
</ul>