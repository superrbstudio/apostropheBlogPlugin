<?php if ($a_blog_event->userHasPrivilege('edit')): ?>
  <?php echo link_to($a_blog_event->getTitle(), 'a_blog_event_admin_edit', $a_blog_event) ?> <?php if (!$a_blog_event->getPublished()): ?> - Draft<?php endif ?>
<?php else: ?>
  <?php echo $a_blog_event->getTitle() ?> <?php if (!$a_blog_event->getPublished()): ?> - Draft<?php endif ?>
<?php endif ?>