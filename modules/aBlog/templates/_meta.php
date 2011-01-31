<ul class="a-blog-item-meta">
  <li class="post-date"><?php echo aDate::pretty($a_blog_post['published_at']); ?></li>
  <li class="post-author">
			<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
			<?php if ($a_blog_post->getAuthor()): ?>
			  <?php echo ($a_blog_post->getAuthor()->getName()) ? $a_blog_post->getAuthor()->getName() : $a_blog_post->getAuthor()  ?>
			<?php endif ?>
	</li>
	<?php  if (sfConfig::get('app_aBlog_disqus_enabled')): ?>
	<li><a class="disqus-comment-count" href="<?php echo url_for('a_blog_post', $a_blog_post) ?>#disqus_thread">#</a></li>   
	<?php endif ?>
</ul>
