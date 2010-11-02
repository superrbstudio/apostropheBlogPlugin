<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('aBlogAdmin/titleAndSlug', array('a_blog_post' => $a_blog_post)) ?>
<?php include_partial('a/globalJavascripts') ?>
