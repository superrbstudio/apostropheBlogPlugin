<ul class="a-event-date-range block">
	<?php if (date('Y-m-d', strtotime($a_event->getStartDate())) != date('Y-m-d', strtotime($a_event->getEndDate()))): ?>
		<li class="start_date">
			<?php echo false !== strtotime($a_event->getStartDate()) ? format_date($a_event->getStartDate(), "f") : '&nbsp;' ?> to
		</li>
		<li class="end_date">
			<?php echo false !== strtotime($a_event->getEndDate()) ? format_date($a_event->getEndDate(), "f") : '&nbsp;' ?>
		</li>
	<?php elseif($a_event->getStartDate() != $a_event->getEndDate()): ?>
    <li class="start_date">
			<?php echo false !== strtotime($a_event->getStartDate()) ? format_date($a_event->getStartDate(), "f") : '&nbsp;' ?> - <?php echo format_date($a_event->getEndDate(), "t") ?>
		</li>		
  <?php else: ?>
		<li class="start_date">
			<?php echo false !== strtotime($a_event->getStartDate()) ? format_date($a_event->getStartDate(), "f") : '&nbsp;' ?>
		</li>			
	<?php endif ?>
</ul>