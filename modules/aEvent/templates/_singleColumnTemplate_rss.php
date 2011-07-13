<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>
<?php include_partial('aEvent/dateRange', array('aEvent' => $aEvent)) ?>
<br/><br/>
<?php foreach($aEvent->Page->getArea('blog-body') as $slot): ?>
<?php // getBasicHtml has basic formatting, which RSS does allow ?>
<?php echo $slot->getBasicHtml() ?>
<?php endforeach ?>
