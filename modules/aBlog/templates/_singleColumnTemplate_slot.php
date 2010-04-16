<?php foreach($aBlogPost->Page->getArea('blog-body') as $slot): ?>
  <?php if(method_exists($slot, 'getSearchText')): ?>
    <?php echo $slot->getSearchText() ?>
  <?php endif ?>
<?php endforeach ?>
