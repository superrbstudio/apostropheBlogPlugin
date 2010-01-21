<?php if ($sf_params->get('year')): ?>
<h2><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h2>
<ul class="a-controls a-blog-browser-controls">
  <li><?php echo link_to('Previous', aTools::getCurrentPage()->getUrl().'?'.http_build_query($params['prev']), array('class' => 'a-btn icon a-arrow-left nobg', )) ?></li>
  <li><?php echo link_to('Next', aTools::getCurrentPage()->getUrl().'?'.http_build_query($params['next']), array('class' => 'a-btn icon a-arrow-right nobg', )) ?></li>
</ul>
<?php endif ?>

<?php if ($a_blog_posts->haveToPaginate()): ?>
<?php echo include_partial('aBlogSlot/pagination', array('pager' => $a_blog_posts, 'params' => $params['pagination'])); ?>
<?php endif ?>

<div style="clear:both;">
<?php foreach ($a_blog_posts->getResults() as $a_blog_post): ?>
<?php echo include_partial('aBlogSlot/post', array('a_blog_post' => $a_blog_post, 'excerpt' => 'true')); ?>
<?php endforeach ?>
</div>

<?php if ($a_blog_posts->haveToPaginate()): ?>
<?php echo include_partial('aBlogSlot/pagination', array('pager' => $a_blog_posts, 'params' => $params['pagination'])); ?>
<?php endif ?>
