<?php $i=1 ?>
<?php foreach($a_blog_category->Groups as $group): ?>
<?php echo $group ?><?php if($i < count($a_blog_category->Groups)): ?>, <?php endif ?>
<?php $i++ ?>
<?php endforeach ?>
