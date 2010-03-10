<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
	[?php slot('a-admin.current-header') ?]
	<th class="a-admin-<?php echo strtolower($field->getType()) ?> a-admin-list-th-<?php echo $name ?>">
	<?php if ($field->isReal()): ?>
	  [?php if ('<?php echo $name ?>' == $sort[0]): ?]
	    [?php echo jq_link_to_function(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), 'getFilters("<?php echo $field->getName() ?>")') ?]
	    [?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?]
    [?php else: ?]
	    [?php echo jq_link_to_function(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), 'getFilters("<?php echo $field->getName() ?>")') ?]
    [?php endif; ?]
	<?php else: ?>
	  [?php echo jq_link_to_function(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), 'getFilters("<?php echo $field->getName() ?>")') ?]
	<?php endif; ?>
  <div id="a-admin-list-th-<?php echo $name ?>-filter"></div>
	</th>
	[?php end_slot(); ?]

<?php echo $this->addCredentialCondition("[?php include_slot('a-admin.current-header') ?]", $field->getConfig()) ?>

<?php endforeach; ?>
