<ul class="a-blog-recentposts">
	<?php foreach ($a_blog_posts as $a_blog_post): ?>
		<li>
			<h3 class="a-blog-post-title"><?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?></h3>
			<p><?php echo ($a_blog_post->getExcerpt()) ? $a_blog_post->getExcerpt() : $a_blog_post->getPreview() ?></p>
			<p><?php echo link_to('Read More', 'a_blog_post_show', $a_blog_post, array('class' => 'a-blog-more')) ?></p>
		</li>
	<?php endforeach ?>
</ul>