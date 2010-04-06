<?php if ($actions = $this->configuration->getValue('edit.actions')): ?>
<?php foreach ($actions as $name => $params): ?>
<?php if ('_new' == $name): ?>
<?php echo $this->addCredentialCondition('[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]', $params) ?>
<?php else: ?>
  <li class="a-admin-action-<?php echo $params['class_suffix'] ?>">
    [?php echo link_to('<?php echo $params['label'] ?>', '@<?php echo $name ?>') ?]
  </li>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>