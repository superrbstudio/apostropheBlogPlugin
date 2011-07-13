<?php // No redundant title and pubdate - CNN doesn't do it ?>
<?php // This beats conditionals all over _meta ?>
<?php echo preg_replace('/\s+/', ' ', trim(strip_tags(get_partial('aEvent/meta', array('aEvent' => $aEvent))))) ?>
<br/><br/>
<?php foreach($aEvent->Page->getArea('blog-body') as $slot): ?>
<?php echo $slot->getText() ?>
<?php endforeach ?>
