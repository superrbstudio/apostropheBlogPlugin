<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_category = isset($a_blog_category) ? $sf_data->getRaw('a_blog_category') : null;
?>
<?php echo link_to($a_blog_category->name, '@a_blog_category_admin_edit?id='.$a_blog_category->id) ?>
