<?php use_helper('jQuery') ?>

<div class="a-blog-post">
  <?php if ($a_blog_post->userHasPrivilege('edit')): ?>
    <?php echo link_to('Edit This Post', 'a_blog_post_admin_edit', $a_blog_post, array('class' => 'a-btn  icon a-blog-btn')) ?>
  <?php endif ?>
  <h3 class="a-blog-post-title"><?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?></h3>
	<ul class="a-blog-post-meta">
		<li class="date"><?php echo date('j F Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
		<li class="time"><?php echo date('g:iA', strtotime($a_blog_post->getPublishedAt())) ?></li>		
	</ul>
  <div class="a-blog-post-body">
		<div class="a-blog-post-excerpt">
			<?php echo (isset($excerpt)) ? $a_blog_post->getExcerpt() : $a_blog_post->getBody() ?>
			<ul class="a-blog-post-tags">
				<li class="title">Tagged: </li>
				<li class="tag"><?php $n=1; foreach ($a_blog_post->getTags() as $tag): ?>
					<?php echo link_to($tag, aTools::getCurrentPage()->getUrl().'?tag='.$tag) ?><?php if ($n < count($a_blog_post->getTags())): ?>, <?php endif ?>
			  <?php $n++; endforeach ?></li>
			</ul>			
		</div>
		
  	<?php if ($a_blog_post->getAttachedMedia()): ?>
			<?php if (in_array('aSlideshowSlot', sfConfig::get('sf_enabled_modules'))): ?>
				<div class="a-blog-post-media">
					<?php include_component('aSlideshowSlot', 'slideshow', array(
						'items' => $a_blog_post->getAttachedMedia(),
						'id' => $a_blog_post->getId(),
						'options' => array('width' => 240, 'height' => 180, 'resizeType' => 'c')
					)) ?>
				</div>
			<?php else: ?>
			  <ul class="a-blog-post-media a-tubes-attached-media">
			  <?php foreach ($a_blog_post->getAttachedMedia() as $media): ?>
			    <li><?php echo image_tag(str_replace(
			      array("_WIDTH_", "_HEIGHT_", "_c-OR-s_", "_FORMAT_"),
			      array('240', '180', 'c', 'jpg',),
			      $media->image
			    )) ?></li>
			  <?php endforeach ?>
			  </ul>
		  <?php endif ?>
		<?php endif ?>

	</div>
</div>
