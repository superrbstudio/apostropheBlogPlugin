<?php use_helper('jQuery') ?>

<div class="a-blog-post">
  <?php if ($a_blog_event->userHasPrivilege('edit')): ?>
    <?php echo link_to('Edit This Post', 'a_blog_event_admin_edit', $a_blog_event, array('class' => 'a-btn icon a-blog')) ?>
  <?php endif ?>
  <h3 class="a-blog-post-title"><?php echo link_to($a_blog_event->getTitle(), 'a_calendar_post', $a_blog_event) ?></h3>
	<ul class="a-blog-post-meta">
		<li class="a-calendar-date"><?php echo date('l', strtotime($a_blog_event->getStartDate())) ?></li>
		<li><?php echo date('F jS Y', strtotime($a_blog_event->getStartDate())) ?></li>
    <?php if($a_blog_event->getStartTime()): ?>
		<li><?php echo date('g:iA', strtotime($a_blog_event->getStartTime())) ?> - 
    <?php if($a_blog_event->getEndTime()): ?><?php echo date('g:iA', strtotime($a_blog_event->getEndTime())) ?></li>
    <?php endif ?>
    <?php endif ?>
	</ul>
  <div class="a-blog-post-body">
		<div class="a-blog-post-excerpt">
			<?php echo (isset($excerpt) && $a_blog_event->getExcerpt()) ? $a_blog_event->getExcerpt() : $a_blog_event->getBody() ?>			
			<?php if ((isset($excerpt) && $a_blog_event->getExcerpt())): ?>
				<span class="a-blog-read-more"><?php echo link_to('Read More', 'a_calendar_post', $a_blog_event, array('class' => 'a-blog-more')) ?></span>
			<?php endif ?>
		</div>
		
  	<?php if ($a_blog_event->getAttachedMedia()): ?>
			<?php if (in_array('aSlideshow', sfConfig::get('sf_enabled_modules'))): ?>
				<div class="a-blog-post-media">
				  <?php include_component('aSlideshow', 'slideshow', array(
						'items' => $a_blog_event->getAttachedMedia(),
						'id' => $a_blog_event->getId(),
						'options' => array('width' => 420, 'height' => 300, 'resizeType' => 'c'),
						'constraints' => array('minimum-width' => 420,'minimum-height' => 300 )
					)) ?>
				</div>
			<?php else: ?>
			  <ul class="a-blog-post-media a-tubes-attached-media">
			  <?php foreach ($a_blog_event->getAttachedMedia() as $media): ?>
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
	<?php if ($a_blog_event->getTags()): ?>
	<ul class="a-blog-post-tags">
		<li class="title">Tagged: </li>
		<li class="tag"><?php $n=1; foreach ($a_blog_event->getTags() as $tag): ?>
			<?php echo link_to($tag, 'aCalendar/index?tag='.$tag) ?><?php if ($n < count($a_blog_event->getTags())): ?>, <?php endif ?>
	  <?php $n++; endforeach ?></li>
	</ul>
	<?php endif ?>
</div>
