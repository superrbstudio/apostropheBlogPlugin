<div id="a-admin-bar" <?php if (count($sf_user->getAttribute('aBlogAdmin.filters', null, 'admin_module'))): ?>class="has-filters"<?php endif ?>>
  <h2 class="a-admin-title you-are-here"><?php echo __('ABlogAdmin List', array(), 'messages') ?></h2>
</div>

Posts 
<?php $appliedFilters = $filters->getAppliedFilters(); ?>
<?php foreach($configuration->getFormFields($filters, 'filter') as $fields): ?>
  <?php foreach ($fields as $name => $field): ?>
    <?php if(isset($appliedFilters[$name])): ?>
      <?php echo $field->getConfig('label', $name) ?>
      <?php foreach($appliedFilters[$name] as $value): ?>
        <?php echo link_to($value, "@a_blog_admin_remove_filter?name=$name&value=$value", array('class' => 'selected')) ?>
      <?php endforeach ?>
    <?php endif ?>
<?php endforeach ?>
<?php endforeach ?>

<script type="text/javascript">
$(document).ready(function() {
  $('a.selected').prepend('<span class="close"></span>')
}); 
</script>


