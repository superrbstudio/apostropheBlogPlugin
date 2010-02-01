<?php foreach($a_blog_post->Categories as $category): ?>
<?php echo link_to($category->name, 'aBlogAdmin/addFilter?filter_field=categories_list&filter_value='.$a_blog_post->Author->id, 'post=true') ?> 
<?php endforeach ?>