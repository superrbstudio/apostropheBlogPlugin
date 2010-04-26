<?php // Just echo the form. You might want to render the form fields differently ?>
<script type="text/javascript" src='/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js' ></script>
<script type="text/javascript">
$('document').ready(function() {
  
  $('#<?php echo $form->getName()."_search" ?>').autocomplete('<?php echo url_for("@a_blog_admin_autocomplete") ?>');
  $('#<?php echo $form->getName()."_search" ?>').result(function(event, data, formatted){
    if (data) {
      $('#<?php echo $form->getName()."_blog_item" ?>').val(data[1]);
      $('#<?php echo $form->getName()."_search" ?>').val(data[2]);
    }
  });
});

</script>
<?php echo $form ?>