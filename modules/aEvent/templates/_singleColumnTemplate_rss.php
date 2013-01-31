<?php
  // Compatible with sf_escaping_strategy: true
  $aEvent = isset($aEvent) ? $sf_data->getRaw('aEvent') : null;
?>
<?php include_partial('aEvent/dateRange', array('aEvent' => $aEvent)) ?>
<br/><br/>
<?php echo $aEvent->getRichTextForAreas('blog-body', sfConfig::get('app_aEvents_feedExcerpts') ? sfConfig::get('app_aEvent_feedExcerptLength', 30) : null) ?>
