<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>

<?php if ($aBlogItem): ?>
  <?php include_partial('aBlogSingleSlot/post', array('aBlogItem' => $aBlogItem, 'options' => $options)) ?>
<?php endif ?>
