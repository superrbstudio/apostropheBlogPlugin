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

<?php if (strlen($aEvent['location'])): ?>
  <?php // It is amazing how often this works well even for something as short as ?>
  <?php // 'Blockley Hall' since the user's location is often known to Google Maps. However ?>
  <?php // it is less useful if all of your locations are 'room 150', etc. with no further ?>
  <?php // information. Naturally full addresses work best ?>
  <?php if (sfConfig::get('app_events_google_maps', true)): ?>
    <h4 class="a-blog-item-location"><?php echo link_to(aString::firstLine($aEvent['location']), 'http://maps.google.com/maps?' . http_build_query(array('q' => preg_replace('/\s+/', ' ', $aEvent['location']))), array('title' => $aEvent['location'])) ?></h4>
  <?php endif ?>
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

<?php include_partial('aEvent/addToGoogleCalendar', array('a_event' => $aEvent)) ?>  
<?php include_partial('aEvent/addIcal', array('a_event' => $aEvent)) ?>  
