<div class="a-calendar-upcomingevents">
  <?php foreach ($a_blog_events as $a_blog_event): ?>
    
    <div class="a-calendar-event">
      <div class="a-calendar-meta">
        <ul>
          <li class="a-calendar-date"><?php echo date('l', strtotime($a_blog_event->getStartDate())) ?></li>
          <li><?php echo date('F jS Y', strtotime($a_blog_event->getStartDate())) ?></li>
          <?php if($a_blog_event->getStartTime()): ?>
          <li><?php echo date('g:iA', strtotime($a_blog_event->getStartTime())) ?> 
          <?php if($a_blog_event->getEndTime()): ?>- <?php echo date('g:iA', strtotime($a_blog_event->getEndTime())) ?></li>
          <?php endif ?>
          <?php endif ?>
        </ul>
      </div>
    
      <div class="a-calendar-event-body">
        <h3 class="a-blog-post-title"><?php echo link_to($a_blog_event->getTitle(), 'a_calendar_post', $a_blog_event) ?></h3>
        <?php if (str_word_count(strip_tags($a_blog_event->getBody())) > 30): ?>
          <?php echo ($a_blog_event->getExcerpt()) ? $a_blog_event->getExcerpt() : $a_blog_event->getPreview(30) ?>
          <span class='a-blog-read-more'><?php echo link_to('Read More', 'a_calendar_post', $a_blog_event, array('class' => 'a-blog-more')) ?></span>
        <?php else: ?>
          <?php echo ($a_blog_event->getBody()) ?>
        <?php endif ?>
      </div>
    </div>
    
  <?php endforeach ?>
</div>

