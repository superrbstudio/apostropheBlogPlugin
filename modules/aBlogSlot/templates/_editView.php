<?php // Just echo the form. You might want to render the form fields differently ?>
<?php echo $form ?>

<script src='/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js'></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
    pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
  });
</script>

<script type="text/javascript">
  $(function() {
    aMultipleSelect('#a-<?php echo $form->getName() ?>', { 'choose-one': 'Add Categories' })
  });
</script>


