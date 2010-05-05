<div class="a-blog-item event">

	<?php if (0): ?>
  <?php if($aEvent->userHasPrivilege('edit')): ?>
  <ul class="a-controls a-blog-post-controls">
		<li><?php echo link_to('Edit', 'a_event_admin_edit', $aEvent, array('class' => 'a-btn icon a-edit flag no-label', )) ?></li>

	 	<?php if($aEvent->userHasPrivilege('delete')): ?>
		<li><?php echo link_to('Delete', 'a_event_admin_delete', $aEvent, array('class' => 'a-btn icon a-delete no-label', 'method' => 'delete', 'confirm' => __('Are you sure you want to delete this event?', array(), 'apostrophe_blog'), )) ?></li>
		<?php endif ?>
	</ul>
	<?php endif ?>
	<?php endif ?>

	<h3 class="a-blog-item-title"><?php echo link_to($aEvent['title'], 'a_event', $aEvent) ?></h3>

	<ul class="a-blog-item-meta">
	  <li class="start-day"><?php echo date('g:iA, l', strtotime($aEvent->getStartDate())) ?></li>
	  <li class="start-date"><?php echo date('F jS, Y', strtotime($aEvent->getStartDate())) ?></li>
		<?php if ($aEvent->getStartDate() != $aEvent->getEndDate()): ?>
			<li class="end-day"><?php echo date('g:iA, l', strtotime($aEvent->getEndDate())) ?></li>
		  <li class="end-date"><?php echo date('F jS, Y', strtotime($aEvent->getEndDate())) ?></li>
		<?php endif ?>
	  <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $aEvent->getAuthor() ?></li>   
	</ul>

	<?php if($options['maxImages'] > 0): ?>
		<div class="a-blog-item-media">
			<?php include_component('aSlideshowSlot', 'slideshow', array(
		  'items' => $aEvent->getMediaForArea('blog-body', 'image', $options['maxImages']),
		  'id' => 'test',
		  'options' => $options['slideshowOptions']
		  )) ?>
		</div>
	<?php endif ?>

  <div class="a-blog-item-excerpt-container">
		<div class="a-blog-item-excerpt">
			<?php echo $aEvent->getTextForArea('blog-body', $options['excerptLength']) ?>
		</div>
		<div class="a-blog-read-more">
			<?php echo link_to('Read More', 'a_event', $aEvent, array('class' => 'a-blog-more')) ?>
		</div>
	</div>
</div>