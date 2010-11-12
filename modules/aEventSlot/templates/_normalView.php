<?php
  // Compatible with sf_escaping_strategy: true
  $aEvents = isset($aEvents) ? $sf_data->getRaw('aEvents') : null;
  $name = isset($name) ? $sf_data->getRaw('name') : null;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
  $page = isset($page) ? $sf_data->getRaw('page') : null;
  $permid = isset($permid) ? $sf_data->getRaw('permid') : null;
  $slot = isset($slot) ? $sf_data->getRaw('slot') : null;
?>
<?php include_partial('a/simpleEditWithVariants', array('pageid' => $page->id, 'name' => $name, 'permid' => $permid, 'slot' => $slot, 'page' => $page)) ?>

<?php foreach ($aEvents as $aEvent): ?>
	<?php $options['slideshowOptions']['idSuffix'] = 'aEvent-'.$permid.'-'.$slot.'-'.$aEvent->getId(); ?>	
	<?php include_partial('aEventSingleSlot/post', array('options' => $options, 'aBlogItem' => $aEvent,)) ?>
<?php endforeach ?>
