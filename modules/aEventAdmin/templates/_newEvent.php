<?php use_helper("a") ?>
<form class="a-blog-admin-new-form" method="POST" action="<?php echo url_for('aEventAdmin/newWithTitle') ?>">
  <?php echo $form ?>
  <div class="a-form-row">
    <ul class="a-controls">
      <li><?php echo a_submit_button(a_('Create')) ?></li>
      <li><?php echo a_js_button(a_('Cancel'), array('a-cancel','icon','a-options-cancel')) ?></li>
    </ul>
  </div>
</form>
<?php a_js_call('aBlogEnableNewForm()') ?>