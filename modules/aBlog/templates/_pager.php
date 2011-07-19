<?php $pager = $sf_data->getRaw('pager') ?>
<?php $pagerUrl = $sf_data->getRaw('pagerUrl') ?>
<?php $max_per_page = $sf_data->getRaw('max_per_page') ?>
<?php $pagerContainerClass = isset($pagerContainerClass) ? ' '.$sf_data->getRaw('pagerContainerClass') : '' ?>

<div class="a-ui a-media-library-controls<?php echo $pagerContainerClass ?>">
  <?php include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>
</div>