<?php $filterForm = new aBlogPostFormFilter() ?>
<?php $filterFieldConfig = $this->configuration->getFormFilterFields($filterForm) ?>
<?php $filterFields = $filterForm->getFields() ?>
<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
	[?php slot('a-admin.current-header') ?]
	<th class="a-admin-<?php echo strtolower($field->getType()) ?> a-column-<?php echo $name ?>">
	<?php if(isset($filterFieldConfig[$name])): ?>
    <?php //This field needs dropdown filters to be applied ?>
    <ul>
      <li><a href="#" class="a-btn a-sort-label">[?php echo __('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>') ?]</a>
        <div class="filternav">
          <hr/>
    <?php if($filterFieldConfig[$name]->isComponent()): ?>
      [?php include_component('<?php echo $this->getModuleName() ?>', 'list_th_<?php echo $name ?>_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]
    <?php elseif($filterFieldConfig[$name]->isPartial()): ?>
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $name ?>_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]
    <?php elseif(in_array($filterFields[$name], array('Enum', 'ForeignKey', 'ManyKey'))): ?>
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_dropdown', array('filters' => $filters, 'name' => '<?php echo $name ?>'  )) ?]    
    <?php endif ?>
        </div>
      </li>
    </ul>
  
  
  <?php elseif ($field->isReal()): ?>
	  [?php if ('<?php echo $name ?>' == $sort[0]): ?]
	    [?php echo jq_link_to_function(__('<?php echo $field->getConfig('label') ?>', array(), '<?php echo $this->getI18nCatalogue() ?>'), 'getFilters("<?php echo $field->getName() ?>")') ?]
	    [?php echo image_tag(((sfConfig::get('app_aAdmin_web_dir'))?sfConfig::get('app_aAdmin_web_dir'):'/apostrophePlugin').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?]
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
