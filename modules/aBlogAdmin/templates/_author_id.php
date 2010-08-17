<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
?>
<?php echo link_to($a_blog_post->Author, '@a_blog_admin_addFilter?name=author_id&value='.$a_blog_post->Author->id, 'post=true') ?>
