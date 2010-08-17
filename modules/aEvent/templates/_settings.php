<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>
<?php echo $form ?>
<script>
aMultipleSelectAll({'choose-one':'Select to Add'});
</script>