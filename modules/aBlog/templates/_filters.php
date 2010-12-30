<?php $type = $sf_data->getRaw('type') ?>
<?php $url = $sf_data->getRaw('url') ?>
<?php $filters = array() ?>

<?php $date = $sf_params->get('day') . ' ' . (($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '') . ' ' . $sf_params->get('year') ?>

<?php $url = aUrl::addParams($url, array('year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'cat' => $sf_params->get('cat'), 'tag' => $sf_params->get('tag'))) ?>
<?php if ($sf_params->get('year') > 0): ?>
  <?php $filters[] = a_('for %date%', array('%date%' => a_remove_filter_button($date, $url, array('year', 'month', 'day')))) ?>
<?php endif ?>
<?php if (strlen($sf_params->get('q'))): ?>
  <?php $filters[] = a_('matching the search %search%', array('%search%' => a_remove_filter_button($sf_params->get('q'), $url, 'q'))) ?>
<?php endif ?>
<?php if (strlen($sf_params->get('cat'))): ?>
  <?php $category = Doctrine::getTable('aCategory')->findOneBySlug($sf_params->get('cat')) ?>
  <?php if ($category): ?>
    <?php $filters[] = a_('in the category %category%', array('%category%' => a_remove_filter_button($category->name, $url, 'cat'))) ?>
  <?php endif ?>
<?php endif ?>
<?php if (strlen($sf_params->get('tag'))): ?>
  <?php $filters[] = a_('with the tag %tag%', array('%tag%' => a_remove_filter_button($sf_params->get('tag'), $url, 'tag'))) ?>
<?php endif ?>
<?php if (count($filters)): ?>
  <h3 class="a-blog-filters"><?php echo a_('You are viewing %type% %filters%', array('%type%' => $type, '%filters%' => implode(' ', $filters))) ?></h3>
<?php endif ?>
