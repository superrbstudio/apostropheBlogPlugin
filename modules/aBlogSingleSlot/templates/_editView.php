<?php // Just echo the form. You might want to render the form fields differently ?>
<script type="text/javascript" src='/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js' ></script>
<script type="text/javascript">
$('document').ready(function() {
  
  $('#<?php echo $form['search']->renderId() ?>').autocomplete('<?php echo url_for("@a_blog_admin_autocomplete") ?>');
  $('#<?php echo $form['search']->renderId() ?>').result(function(event, data, formatted){
    if (data) {
      $('#<?php echo $form['blog_item']->renderId() ?>').val(data[1]);
      $('#<?php echo $form['search']->renderId() ?>').val(data[2]);
    }
  });
});

</script>
<?php echo $form ?>