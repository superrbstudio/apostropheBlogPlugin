<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_category = isset($a_blog_category) ? $sf_data->getRaw('a_blog_category') : null;
  $i = isset($i) ? $sf_data->getRaw('i') : null;
?>
<?php $i=1 ?>
<?php foreach($a_blog_category->Users as $user): ?>
<?php echo $user ?><?php if($i < count($a_blog_category->Users)): ?>, <?php endif ?>
<?php $i++ ?>
<?php endforeach ?>
