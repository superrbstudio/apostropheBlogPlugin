<h3 class="a-blog-item-title"><?php echo link_to($aEvent['title'], 'a_event_post', $aEvent) ?></h3>

<ul class="a-blog-item-meta">
  <li class="start-day"><?php echo aDate::dayAndTime($aEvent->getStartDate()) ?></li>
  <li class="start-date"><?php echo aDate::dayMonthYear($aEvent->getStartDate()) ?><?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?> &mdash;<?php endif ?></li>
	<?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?>
		<li class="end-day"><?php echo aDate::dayAndTime($aEvent->getEndDate()) ?></li>
	  <li class="end-date"><?php echo aDate::dayMonthYear($aEvent->getEndDate()) ?></li>
	<?php endif ?>
	<?php if (0): ?>
	<?php // Events authors are not important, turned off for now ?>
  	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $aEvent->getAuthor() ?></li>   			
	<?php endif ?>
</ul>

<?php if($options['maxImages'] && $aEvent->hasMedia()): ?>		
	<div class="a-blog-item-media">
		<?php include_component('aSlideshowSlot', 'slideshow', array(
	  'items' => $aEvent->getMediaForArea('blog-body', 'image', $options['maxImages']),
	  'id' => 'a-slideshow-blogitem-'.$aEvent['id'],
	  'options' => $options['slideshowOptions']
	  )) ?>
	</div>
<?php endif ?>

 <div class="a-blog-item-excerpt-container">
	<div class="a-blog-item-excerpt">
		<?php echo $aEvent->getTextForArea('blog-body', $options['excerptLength']) ?>
	</div>
	<div class="a-blog-read-more">
		<?php echo link_to('Read More', 'a_event_post', $aEvent, array('class' => 'a-blog-more')) ?>
	</div>
</div>
