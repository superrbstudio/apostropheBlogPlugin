<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>
<?php $startDate = aDate::dayMonthYear($aEvent->getStartDate()) ?>
<?php $endDate = aDate::dayMonthYear($aEvent->getEndDate()) ?>
<?php if (!$aEvent->isAllDay()): ?>
  <?php $startTime = aDate::time($aEvent->getStartTime()) ?>
  <?php $endTime = aDate::time($aEvent->getEndTime()) ?>
<?php endif ?>

<ul class="a-blog-item-meta">
  <li class="start-date"><?php echo $startDate ?></li>
	<?php if ($startDate == $endDate): ?>
		<?php if (isset($startTime)): ?>
	    <?php echo $startTime ?> &ndash; <?php echo $endTime ?></li>
		<?php endif ?>
	<?php else: ?>
		<?php if (isset($startTime)): ?>
		  <li class="event-time"><?php echo $startTime ?> &ndash; <?php echo $endTime ?></li><li class="end-date"><?php echo $endDate ?></li>
		<?php else: ?>
	    <li class="end-date">&ndash; <?php echo $endDate ?></li>
	  <?php endif ?>
	<?php endif ?>

	<?php if (0): ?>
	<?php // Events authors are not important to end users, turned off for now ?>
  	<li class="author"><?php echo __('Posted By:', array(), 'apostrophe') ?> <?php echo $aEvent->getAuthor() ?></li>   			
	<?php endif ?>
</ul>
