<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['count'])): ?>
  <?php foreach ($aEvents as $aEvent): ?>
    <div class="a-blog-item event">
        <?php include_partial('aEvent/'.$aEvent['template'].'_slot', array('aEvent' => $aEvent, 'options' => $options)) ?>
        <div class="a-blog-read-more">
          <?php echo link_to('Read More', 'a_event', $aEvent, array('class' => 'a-blog-more')) ?>
        </div>
    </div>
  <?php endforeach ?>
<?php endif ?>
