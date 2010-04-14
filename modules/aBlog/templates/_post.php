<?php use_helper('jQuery') ?>

<div class="a-blog-post<?php echo $a_blog_post->getAttachedMedia()? ' contains-media':'';?>">
	
	<h3 class="a-blog-post-title"><?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?></h3>
	
	<ul class="a-blog-post-meta">
		<li class="date"><?php echo date('l F jS Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
		<li class="author">Posted By: <?php echo $a_blog_post->getAuthor() ?></li>
		<?php if ($a_blog_post->userHasPrivilege('edit')): ?>
		<li class="edit">
	    <?php echo link_to('Edit Post', 'a_blog_post_admin_edit', $a_blog_post, array('class' => 'a-btn  icon a-blog-btn a-edit-post')) ?>
	  </li>
	  <?php endif ?>		
	</ul>

  <div class="a-blog-post-body">
	
			<?php if ($a_blog_post->getAttachedMedia()): ?>
				<div class="a-blog-post-media">
				  <?php include_component('aSlideshowSlot', 'slideshow', array(
						'items' => $a_blog_post->getAttachedMedia(),
						'id' => $a_blog_post->getId(),
						'options' => array('width' => (sfConfig::get('app_aBlog_post_list_slideshow_width'))? sfConfig::get('app_aBlog_post_list_slideshow_width'):320, 'height' => (sfConfig::get('app_aBlog_post_list_slideshow_height'))? sfConfig::get('app_aBlog_post_list_slideshow_height'):240, 'resizeType' => 'c'),
						'constraints' => array('minimum-width' => (sfConfig::get('app_aBlog_post_list_slideshow_width'))? sfConfig::get('app_aBlog_post_list_slideshow_width'):320,'minimum-height' => (sfConfig::get('app_aBlog_post_list_slideshow_height'))? sfConfig::get('app_aBlog_post_list_slideshow_height'):240 )
					)) ?>
				</div>
			<?php endif ?>
			
		<div class="a-blog-post-excerpt">
      <?php if(isset($full)): ?>
      <?php echo $a_blog_post->getBody() ?>
      <?php else: ?>
        <?php echo (isset($excerpt) && $a_blog_post->getExcerpt()) ? $a_blog_post->getExcerpt() : $a_blog_post->getPreview(250) ?>
        <?php if ((isset($excerpt) && $a_blog_post->getExcerpt())): ?>
  				<div class="a-blog-read-more"><?php echo link_to('Read More', 'a_blog_post', $a_blog_post, array('class' => 'a-blog-more')) ?></div>
    		<?php elseif (count(explode(" ",$a_blog_post->getBody())) > 250): ?>
      		<div class="a-blog-read-more"><?php echo link_to('Read More', 'a_blog_post', $a_blog_post, array('class' => 'a-blog-more')) ?></div>
        <?php endif ?>
      <?php endif ?>
		</div>
		
	</div>
	
	<?php if ($a_blog_post->getTags()): ?>
	<ul class="a-blog-post-tags">
		<li class="title">Tagged: </li>
		<li class="tag"><?php $n=1; foreach ($a_blog_post->getTags() as $tag): ?>
			<?php echo link_to($tag, 'aBlog/index?tag='.$tag) ?><?php if ($n < count($a_blog_post->getTags())): ?>, <?php endif ?>
	  <?php $n++; endforeach ?></li>
	</ul>
	<?php endif ?>
</div>
