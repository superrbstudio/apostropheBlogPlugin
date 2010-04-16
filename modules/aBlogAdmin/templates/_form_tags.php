<?php use_helper('I18N') ?>

<h4>Tags</h4>

<?php echo $form['tags']->render() ?>
<?php echo $form['tags']->renderError() ?>

<script src='/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js'></script>
<script type="text/javascript" charset="utf-8">
  $(function() {
    pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
  });
</script>
<?php include_component('aBlogAdmin','tagList', array('a_blog_post' => $form->getObject())) ?>
