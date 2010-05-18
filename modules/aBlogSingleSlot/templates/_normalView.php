<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['blog_item'])): ?>
  <?php $full = (isset($options['full']) && $options['full']) ?>
  <?php $suffix = $full ? '' : '_slot' ?>  
  <?php // TODO: passing a variable as both underscore and intercap is silly clean this up make the partials consistent but look out for overrides ?>
  <?php include_partial('aBlog/'.$options['template'].$suffix, array('aBlogPost' => $aBlogItem, 'a_blog_post' => $aBlogItem, 'edit' => false, 'options' => $options)) ?>
<?php endif ?>
