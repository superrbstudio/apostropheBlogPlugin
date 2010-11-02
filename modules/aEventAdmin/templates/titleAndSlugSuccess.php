<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>
<?php use_helper("a") ?>
<?php include_partial('aBlogAdmin/titleAndSlug', array('a_event' => $a_event)) ?>
<?php include_partial('a/globalJavascripts') ?>
