<?php
  // Compatible with sf_escaping_strategy: true
  $form = isset($form) ? $sf_data->getRaw('form') : null;
?>
<?php echo $form ?>
<script type="text/javascript" charset="utf-8">
	aMultipleSelectAll({'choose-one':'Select to Add'});
</script>