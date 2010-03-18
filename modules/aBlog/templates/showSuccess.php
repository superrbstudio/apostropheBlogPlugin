<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php echo include_partial('aBlog/post', array('a_blog_post' => $aBlogPost)) ?>

<div style="clear:both;">
<?php echo include_component('aComment', 'comments', array('object' => $aBlogPost)) ?>
<?php echo include_component('aComment', 'commentForm', array('object' => $aBlogPost)) ?>
</div>