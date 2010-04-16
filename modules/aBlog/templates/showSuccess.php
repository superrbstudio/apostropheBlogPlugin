<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php echo include_partial('aBlog/post', array('a_blog_post' => $aBlogPost)) ?>

<?php if($aBlogPost['allow_comments']): ?>

<?php endif ?>