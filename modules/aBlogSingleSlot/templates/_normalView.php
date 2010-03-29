<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['blog_post'])): ?>
  <?php include_partial('aBlog/'.$aBlogPost['template'].'_rss', array('aBlogPost' => $aBlogPost)) ?>
<?php endif ?>
