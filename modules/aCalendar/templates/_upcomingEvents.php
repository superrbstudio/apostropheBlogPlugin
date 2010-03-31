<div class="a-calendar-upcomingevents">
  <?php foreach ($a_blog_events as $a_blog_event): ?>
    
    <div class="a-calendar-event">
	    <h3 class="a-blog-post-title"><?php echo link_to($a_blog_event->getTitle(), 'a_calendar_post', $a_blog_event) ?></h3>
      <div class="a-calendar-meta">
        <ul>
      		<li class="a-calendar-date">
      			<span class="event-start-date">
      				<?php echo date((sfConfig::get('app_aCalendar_event_date_format'))? sfConfig::get('app_aCalendar_event_date_format'):'l, F jS Y', strtotime($a_blog_event->getStartDate())) ?>
      				<?php echo date("g:iA",strtotime($a_blog_event->getStartTime())) ?>
      				&ndash;
      			</span>
            <?php if ($a_blog_event->getEndDate() || $a_blog_event->getEndTime()): ?>
      			<span class="event-end-date">
      				<?php echo date((sfConfig::get('app_aCalendar_event_date_format'))? sfConfig::get('app_aCalendar_event_date_format'):'l, F jS Y', strtotime($a_blog_event->getEndDate())) ?>
      				<?php echo date("g:iA",strtotime($a_blog_event->getEndTime())) ?>
      			</span>
            <?php endif ?>
      		</li>
        </ul>
      </div>
    
      <div class="a-calendar-event-body">
        <?php if (str_word_count(strip_tags($a_blog_event->getBody())) > 30): ?>
          <?php echo ($a_blog_event->getExcerpt()) ? $a_blog_event->getExcerpt() : $a_blog_event->getPreview(30) ?>
          <div class='a-blog-read-more'><?php echo link_to('Read More', 'a_calendar_post', $a_blog_event, array('class' => 'a-blog-more')) ?></div>
        <?php else: ?>
          <?php echo ($a_blog_event->getBody()) ?>
        <?php endif ?>
      </div>
    </div>
    
  <?php endforeach ?>
</div>