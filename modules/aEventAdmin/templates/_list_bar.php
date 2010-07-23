<div class="a-admin-title-sentence">
	<h3> 	
		<?php $appliedFilters = $filters->getAppliedFilters(); ?>
    <?php $fields = $configuration->getFormFilterFields($filters) ?>

		<?php if ($appliedFilters): ?>
			You are viewing events
		<?php else: ?>
			You are viewing all events
		<?php endif ?>	

  <?php $n=1; foreach($appliedFilters as $name => $values): ?>
    <?php $field = $fields[$name] ?>
    <?php echo $field->getConfig('label', $name) ?>
    <?php foreach($values as $value): ?>
      <?php echo link_to($value, "@a_event_admin_removeFilter?name=$name&value=$value", array('class' => 'selected')) ?><?php if ($n < count($appliedFilters)): ?>,<?php endif ?>
    <?php endforeach ?>
	<?php endforeach ?>
	</h3>
</div>