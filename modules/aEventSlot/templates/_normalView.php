<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['count'])): ?>
	<?php foreach ($aEvents as $aEvent): ?>
		<?php include_partial('aEvent/'.$options['template'].'_slot', array('aEvent' => $aEvent, 'options' => $options)) ?>
	<?php endforeach ?>
<?php endif ?>
