<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php echo include_partial('aEvent/post', array('a_event' => $aEvent)) ?>

<?php if($aEvent['allow_comments']): ?>
<div style="clear:both;">

</div>
<?php endif ?>