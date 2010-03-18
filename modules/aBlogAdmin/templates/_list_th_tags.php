<?php //Popular tags will go here eventually ?>
<?php $letter = ''; ?>

<?php foreach($filters['tags_list']->getWidget()->getChoices() as $id => $choice): ?>
<?php   if(strtoupper($choice[0]) == $letter): ?>,<?php   else: ?>
<?php     if(strtoupper($choice[0]) != 'A'): ?></span><?php endif ?>
<?php   $letter = strtoupper($choice[0]) ?>
<span>
  <b><?php echo $letter ?></b>
<?php   endif ?>
<?php echo link_to($choice, 'aBlogAdmin/addFilter?name=tags_list&value='.$id, 'post=true') ?>
<?php endforeach ?>

