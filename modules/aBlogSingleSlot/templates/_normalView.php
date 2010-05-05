<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['blog_item'])): ?>
  <?php include_partial('aBlog/'.$aBlogItem['template'].'_slot', array('aBlogPost' => $aBlogItem, 'options' => $options)) ?>
<?php endif ?>
