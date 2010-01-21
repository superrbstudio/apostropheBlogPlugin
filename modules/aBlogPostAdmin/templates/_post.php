<?php // For some reason link_to_if fails here ?>
<?php if ($a_blog_post->userHasPrivilege('edit')): ?>
  <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post_admin_edit', $a_blog_post) ?> <?php if (!$a_blog_post->getPublished()): ?> - Draft<?php endif ?>
<?php else: ?>
  <?php echo $a_blog_post->getTitle() ?> <?php if (!$a_blog_post->getPublished()): ?> - Draft<?php endif ?>
<?php endif ?>