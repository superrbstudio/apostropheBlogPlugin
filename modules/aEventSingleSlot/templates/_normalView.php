<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['blog_item'])): ?>
  <?php include_partial('aEvent/'.$aBlogItem['template'].'_rss', array('aEvent' => $aBlogItem)) ?>
<?php endif ?>
