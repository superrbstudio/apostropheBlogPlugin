<?php use_helper('a') ?>
<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">

  <?php if($a_event->userHasPrivilege('edit')): ?>
  <ul class="a-controls a-blog-post-controls">
		<li><?php echo link_to('Edit', 'a_event_admin_edit', $a_event, array('class' => 'a-btn icon a-edit flag no-label', )) ?></li>

	 	<?php if($a_event->userHasPrivilege('delete')): ?>
		<li><?php echo link_to('Delete', 'a_event_admin_delete', $a_event, array('class' => 'a-btn icon a-delete no-label', 'method' => 'delete', 'confirm' => __('Are you sure you want to delete this event?', array(), 'apostrophe_blog'), )) ?></li>
		<?php endif ?>
	</ul>
	<?php endif ?>

  <ul class="a-blog-item-meta">
    <li class="start-day"><?php echo date('l', strtotime($a_event->getStartDate())) ?></li>
    <li class="start-date"><?php echo date('F jS Y', strtotime($a_event->getStartDate())) ?></li>
    <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $a_event->getAuthor() ?></li>   
  </ul>
  <h3 class="a-blog-item-title">
    <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
  </h3>
<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event)) ?>
</div>

