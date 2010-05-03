<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
  <?php if (isset($values['count'])): ?>

  <?php foreach ($aEvents as $aEvent): ?>
    <div class="a-blog-item event">
      <h2 class="a-blog-item-title"><?php echo link_to($aEvent['title'], 'a_event', $aEvent) ?></h2>
        <ul class="a-blog-item-meta">
          <li class="date"><?php echo aDate::pretty($aEvent['published_at']) ?></li>
        </ul>
      <?php if (false): ?>
        <div class="a-blog-event-media">

        </div>
      <?php endif ?>

      <div class="a-blog-item-excerpt-container">
        <?php include_partial('aEvent/'.$aEvent['template'].'_slot', array('aEvent' => $aEvent, 'options' => $options)) ?>
        <div class="a-blog-read-more">
          <?php echo link_to('Read More', 'a_event', $aEvent, array('class' => 'a-blog-more')) ?>
        </div>
      </div>
    </div>

  <?php endforeach ?>
<?php endif ?>
