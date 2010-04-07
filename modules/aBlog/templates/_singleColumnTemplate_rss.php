<?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?> by <?php echo $aBlogPost->Author ?>
<br/>
<?php echo $aBlogPost['published_at'] ?>
<br/><br/>
<?php foreach($aBlogPost->Page->getArea('body') as $slot): ?>
<?php if(method_exists($slot, 'getSearchText')): ?>
<?php echo $slot->getSearchText() ?>
<?php endif ?>
<?php endforeach ?>
