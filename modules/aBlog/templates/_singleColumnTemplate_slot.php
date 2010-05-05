<div class="a-blog-item post<?php echo ($aBlogPost->hasMedia())? ' has-media':''; ?>">
  <h3 class="a-blog-item-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h3>
  <ul class="a-blog-item-meta">
		<li class="date"><?php echo aDate::pretty($aBlogPost['published_at']) ?></li>
  </ul>
	<?php if($options['maxImages'] > 0): ?>
		<div class="a-blog-item-media">
		<?php include_component('aSlideshowSlot', 'slideshow', array(
		  'items' => $aBlogPost->getMediaForArea('blog-body', 'image', $options['maxImages']),
		  'id' => 'test',
		  'options' => $options['slideshowOptions']
		  )) ?>
		</div>
	<?php endif ?>
  <div class="a-blog-item-excerpt-container">
		<div class="a-blog-item-excerpt">
			<?php echo $aBlogPost->getTextForArea('blog-body', $options['excerptLength']) ?>
		</div>
    <div class="a-blog-read-more">
      <?php echo link_to('Read More', 'a_blog_post', $aBlogPost, array('class' => 'a-blog-more')) ?>
    </div>
  </div>
</div>
