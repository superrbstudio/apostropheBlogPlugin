<?php // No redundant title and pubdate - CNN doesn't do it '?>
<?php foreach($aBlogPost->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getText() ?>
<?php endforeach ?>
