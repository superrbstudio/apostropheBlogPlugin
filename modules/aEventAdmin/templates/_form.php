<?php echo rand(0, 500) ?>
<?php use_helper('jQuery') ?>
<?php echo jq_form_remote_tag(array('url' => '@a_event_admin_update?slug='.$a_event['slug'], 'update' => 'a-admin-blog-post-form'), array('id'=>'a-admin-form')) ?>
<?php if (!$form->getObject()->isNew()): ?>
  <input type="hidden" name="sf_method" value="PUT" />
<?php endif; ?>
<div class="a-blog-post-title">
  <h2>Title</h2>
  <?php echo $form['title']->render() ?>
  <?php echo $form['title']->renderError() ?>
</div>

<div class="a-blog-post-slug">
  <h2>Permalink</h2>
  <?php echo $form['slug']->getWidget()->render('a_event[slug]', $a_event['slug']) ?>
  <?php //echo $form['slug']->render() ?>
  <?php echo $form['slug']->renderError() ?>
</div>

<?php include_partial('aBlogAdmin/optionsForm', array('a_event' => $a_event, 'form' => $form)) ?>

<script type="text/javascript">
      $(function () {
      $('#a-admin-form').change(function() {
        jQuery.ajax({
            type:'POST',
            dataType:'html',
            data:jQuery(this).serialize(),
            success:function(data, textStatus){
            jQuery('#a-admin-blog-post-form').html(data);
          },
          url: '<?php echo url_for('@a_event_admin_update?slug='.$a_event['slug']) ?>'
        });
      });
    });
</script>