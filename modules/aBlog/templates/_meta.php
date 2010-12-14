<ul class="a-blog-item-meta">
  <li class="post-date"><?php echo aDate::pretty($a_blog_post['published_at']); ?></li>
  <li class="post-author">
			<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
			<?php echo ($a_blog_post->getAuthor()->getName()) ? $a_blog_post->getAuthor()->getName() : $a_blog_post->getAuthor()  ?>
	</li>   
</ul>
