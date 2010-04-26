<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<h2><?php echo $aBlogItem['title'] ?></h2>
<?php if (isset($values['blog_item'])): ?>
  <?php include_partial('aBlog/'.$aBlogItem['template'].'_slot', array('aBlogPost' => $aBlogItem)) ?>
<?php endif ?>
