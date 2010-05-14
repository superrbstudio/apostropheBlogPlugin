<?php echo link_to($aEvent['title'], 'a_blog_post', $aEvent) ?> by <?php echo $aEvent->Author ?>
<br/>
<?php echo $aEvent['published_at'] ?>
<br/><br/>
<?php foreach($aEvent->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getText() ?>
<?php endforeach ?>
