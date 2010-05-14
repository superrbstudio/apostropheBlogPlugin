<div class="a-admin-filter-sentence">
	<h3> 	
		<?php echo __('Event Admin:', array(), 'messages') ?>

		<?php $appliedFilters = $filters->getAppliedFilters(); ?>

		<?php if ($appliedFilters): ?>
			You are viewing events
		<?php else: ?>
			You are viewing all events
		<?php endif ?>	

		<?php $n=1; foreach($configuration->getFormFields($filters, 'filter') as $fields): ?>
		  <?php foreach ($fields as $name => $field): ?>
		    <?php if(isset($appliedFilters[$name])): ?>
		      <?php echo $field->getConfig('label', $name) ?>
		      <?php foreach($appliedFilters[$name] as $value): ?>
		        <?php echo link_to($value, "@a_event_admin_removeFilter?name=$name&value=$value", array('class' => 'selected')) ?><?php if ($n < count($appliedFilters)): ?>,<?php endif ?>
		      <?php $n++; endforeach ?>
		    <?php endif ?>
			<?php endforeach ?>
		<?php endforeach ?>
	</h3>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	  $('a.selected').prepend('<span class="close"></span>');
	});
</script>