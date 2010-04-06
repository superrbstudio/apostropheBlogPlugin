<?php use_helper('a') ?>
<div class="a-blog-post">
  <h2 class="a-blog-post-title">
    <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
  </h2>
  <ul class="a-blog-post-meta">
    <li class="date"><?php echo date('l F jS Y', strtotime($a_event->getPublishedAt())) ?></li>
    <li class="author">Posted By: <?php echo $a_event->getAuthor() ?></li>   
  </ul>

<?php include_partial('aEvent/'.$a_event->getTemplate(), array('a_event' => $a_event)) ?>

</div>


