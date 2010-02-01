<?php foreach($a_blog_post->Editors as $editor): ?>
<?php echo link_to($editor->username, 'aBlogAdmin/addFilter?filter_field=editors_list&filter_value='.$editor->id, 'post=true') ?> 
<?php endforeach ?>