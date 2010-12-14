<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>

<?php $startDate = aDate::dayMonthYear($aEvent->getStartDate()) ?>
<?php $endDate = aDate::dayMonthYear($aEvent->getEndDate()) ?>
<?php if (!$aEvent->isAllDay()): ?>
  <?php $startTime = aDate::time($aEvent->getStartTime()) ?>
  <?php $endTime = aDate::time($aEvent->getEndTime()) ?>
	<?php $allDay = false ?>
<?php else: ?>
	<?php $allDay = true ?>
<?php endif ?>

<ul class="a-blog-item-meta">

  <li class="post-date post-start-date<?php ($allDay) ? ' all-day-event' : '' ?>"><?php echo $startDate ?></li>

	<?php if ($startDate == $endDate): ?>
		<?php if (isset($startTime)): ?>
	    <li class="post-time"><?php echo $startTime ?> &ndash; <?php echo $endTime ?></li>
		<?php endif ?>
	<?php else: ?>
		<?php if (isset($startTime)): ?>
	  	<li class="post-time post-start-time"><?php echo $startTime ?></li>				
			<li class="post-date post-end-date"><?php echo $endDate ?></li>
			<li class="post-time post-end-time"><?php echo $endTime ?></li>
		<?php else: ?>
	    <li class="post-date post-end-date"><?php echo $endDate ?></li>
	  <?php endif ?>
	<?php endif ?>

	<?php if (strlen($aEvent['location'])): ?>
	  <?php // It is amazing how often this works well even for something as short as ?>
	  <?php // 'Blockley Hall' since the user's location is often known to Google Maps. However ?>
	  <?php // it is less useful if all of your locations are 'room 150', etc. with no further ?>
	  <?php // information. Naturally full addresses work best ?>
    <li class="post-location">
			<?php echo aString::firstLine($aEvent['location']) ?>
  		<?php if (sfConfig::get('app_events_google_maps', true)): ?>
				<?php echo link_to('<span class="icon"></span>'.a_('Google Maps'), 'http://maps.google.com/maps?' . http_build_query(array('q' => preg_replace('/\s+/', ' ', $aEvent['location']))), array('title' => a_('View this location with Google Maps.'), 'class' => 'a-btn lite alt mini icon a-google-maps', )) ?>
		  <?php endif ?>
		</li>
	<?php endif ?>

	<?php /* Events generally don't display the author, but you can if necessary.  ?>
 	<li class="post-author">
		<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
		<?php echo ($aEvent->getAuthor()->getName()) ? $aEvent->getAuthor()->getName() : $aEvent->getAuthor()  ?>
	</li>   			
	<?php //*/ ?>
	
	<li class="post-extra">
		<?php include_partial('aEvent/addToGoogleCalendar', array('a_event' => $aEvent)) ?> 
	</li>

	<li class="post-extra">
		<?php include_partial('aEvent/addIcal', array('a_event' => $aEvent)) ?>  
	</li>
	
</ul>