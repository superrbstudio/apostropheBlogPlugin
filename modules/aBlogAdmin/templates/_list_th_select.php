<?php foreach($filters[$name]->getWidget()->getChoices() as $id => $choice): ?>
<?php if($choice != ''): ?>
<li><?php echo link_to($choice, 'aBlogAdmin/addFilter?name='.$name.'&value='.$id, 'post=true') ?></li>
<?php endif ?>
<?php endforeach ?>