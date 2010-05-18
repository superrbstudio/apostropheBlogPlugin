<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['blog_item'])): ?>
  <?php include_partial('aEvent/'.$options['template'].'_singleSlot', array('aEvent' => $aBlogItem, 'options' => $options)) ?>
<?php endif ?>
