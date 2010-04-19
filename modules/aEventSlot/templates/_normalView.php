<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
  <?php if (isset($values['count'])): ?>

  <?php foreach ($aBlogEvents as $aBlogEvent): ?>
    <div class="a-blog-event">
      <h3 class="a-blog-event-title"><?php echo link_to($aBlogEvent['title'], 'a_blog_post', $aBlogEvent) ?></h3>
        <ul class="a-blog-event-meta">
          <li class="date"><?php echo aDate::pretty($aBlogEvent['published_at']) ?></li>
        </ul>
      <?php if (false): ?>
        <div class="a-blog-event-media">

        </div>
      <?php endif ?>

      <div class="a-blog-event-excerpt-container">
        <?php include_partial('aBlog/'.$aBlogEvent['template'].'_slot', array('aBlogEvent' => $aBlogEvent)) ?>
        <div class="a-blog-read-more">
          <?php echo link_to('Read More', 'a_blog_event', $aBlogEvent, array('class' => 'a-blog-more')) ?>
        </div>
      </div>
    </div>

  <?php endforeach ?>
<?php endif ?>
