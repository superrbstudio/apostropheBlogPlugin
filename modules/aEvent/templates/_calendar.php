<table class="a-calendar">
	<thead>
	<tr class="month">
		<th colspan="7">
			<a href="<?php echo url_for('aEvent/index?'.http_build_query($calendar['params']['prev'])) ?>" class="a-arrow-btn icon a-arrow-left previous-month"/>Previous</a>
			<h4 class="title"><?php echo $calendar['month'] ?> <?php echo $calendar['year'] ?></h4>
			<a href="<?php echo url_for('aEvent/index?'.http_build_query($calendar['params']['next'])) ?>" class="a-arrow-btn icon a-arrow-right next-month">Next</a>
		</th>
	</tr>
	<tr class="days">
		<th class="day sunday">Su</th>
		<th class="day monday">M</th>
		<th class="day tuesday">T</th>
		<th class="day wedsnesday">W</th>
		<th class="day thursday">Th</th>
		<th class="day friday">F</th>
		<th class="day saturday">S</th>																								
	</tr>	
	</thead>
	<tbody>
	<?php $w=0; $d=0; foreach ($calendar['events']->getEventCalendar() as $week): ?>
		<tr class="week-<?php echo $w; ?>">
			<?php foreach ($week as $eventDate => $event): ?>
				<td class="day day-<?php echo $d; ?>
					<?php echo (date('m', strtotime($eventDate)) == date('m', strtotime($calendar['month']))) ? 'current-month':'not-current-month'; ?>
					<?php echo (date('mdy', strtotime($eventDate)) == date('mdy')) ? 'today':''; ?>
					<?php echo (date('d', strtotime($eventDate)) == $sf_request->getParameter('day'))? 'selected':'' ?>">
					<?php if (!empty($event)): ?>
						<span class="has-events"><a href="<?php echo url_for('aEvent/index?'. http_build_query(array('year' => date('Y', strtotime($eventDate)), 'month' => date('m', strtotime($eventDate)), 'day' => date('d', strtotime($eventDate))))) ?>"><?php echo date('d', strtotime($eventDate)) ?></a></span>
					<?php else: ?>
						<span><?php echo date('d', strtotime($eventDate)) ?></span>
					<?php endif ?>					
				</td>
			<?php $d++; endforeach ?>
		</tr>
	<?php $w++; endforeach ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="7">
				<a href="<?php echo url_for('aEvent/index?'. http_build_query(array('year' => date('Y'), 'month' => date('m'), 'day' => date('d')))) ?>" class="a-btn icon a-events day-<?php echo date('d') ?> mini nobg"><span class="day"></span>Today</a>
			</td>
		</tr>
	</tfoot>
</table>
