<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['count'])): ?>
<?php foreach($aEvents as $aEvent): ?>
  <br/>
  <?php include_partial('aEvent/'.$aEvent['template'].'_rss', array('aEvent' => $aEvent)) ?>
  <br/>
<?php endforeach ?>
<?php endif ?>
