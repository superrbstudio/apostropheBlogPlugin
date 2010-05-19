<?php use_helper('a', 'I18N') ?>
<?php $catClass = ""; foreach ($a_blog_post->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item post <?php echo $a_blog_post->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">

  <?php if($a_blog_post->userHasPrivilege('edit')): ?>
  <ul class="a-controls a-blog-post-controls">
		<li><?php echo link_to('Edit', 'a_blog_admin_edit', $a_blog_post, array('class' => 'a-btn icon a-edit flag no-label', )) ?></li>

	 	<?php if($a_blog_post->userHasPrivilege('delete')): ?>
		<li><?php echo link_to('Delete', 'a_blog_admin_delete', $a_blog_post, array('class' => 'a-btn icon a-delete no-label', 'method' => 'delete', 'confirm' => __('Are you sure you want to delete this post?', array(), 'apostrophe_blog'), )) ?></li>
		<?php endif ?>
	</ul>
	<?php endif ?>

<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post, 'edit' => false)) ?>
</div>


