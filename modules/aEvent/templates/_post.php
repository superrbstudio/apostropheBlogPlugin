<?php use_helper('a') ?>
<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">
  <ul class="a-blog-item-meta">
    <li class="day"><?php echo date('l', strtotime($a_event->getPublishedAt())) ?></li>
    <li class="date"><?php echo date('F jS Y', strtotime($a_event->getPublishedAt())) ?></li>
    <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $a_event->getAuthor() ?></li>   
  </ul>
  <h3 class="a-blog-item-title">
    <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
  </h3>
<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event)) ?>
</div>

