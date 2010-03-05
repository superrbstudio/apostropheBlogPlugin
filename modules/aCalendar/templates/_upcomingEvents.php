<div class="a-calendar-upcomingevents">
  <?php foreach ($a_blog_events as $a_blog_event): ?>
    
    <div class="a-calendar-event">
	    <h3 class="a-blog-post-title"><?php echo link_to($a_blog_event->getTitle(), 'a_calendar_post', $a_blog_event) ?></h3>
      <div class="a-calendar-meta">
        <ul>
	
	        <li class="a-calendar-date"><?php echo date((sfConfig::get('app_aCalendar_event_date_format'))? sfConfig::get('app_aCalendar_event_date_format'):'l, F jS Y', strtotime($a_blog_event->getStartDate())) ?></li>
          <?php if($a_blog_event->getStartTime()): ?>
          <li><?php echo date((sfConfig::get('app_aCalendar_event_time_format'))? sfConfig::get('app_aCalendar_event_time_format'):'g:iA', strtotime($a_blog_event->getStartTime())) ?> 
          <?php if($a_blog_event->getEndTime()): ?>- <?php echo date('g:iA', strtotime($a_blog_event->getEndTime())) ?></li>
          <?php endif ?>
          <?php endif ?>
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