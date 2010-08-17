<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
?>
<?php echo link_to($a_event->Author, '@a_event_admin_addFilter?name=author_id&value='.$a_event->Author->id, 'post=true') ?>
