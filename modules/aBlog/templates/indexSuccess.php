<?php # BLOG POSTS ========================================================= ?>

<?php slot('body_class') ?>a-blog a-blog-posts <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
<div class="a-subnav-wrapper blog">
	
	<div class="a-subnav-inner">
		<?php include_component('aBlog', 'tagSidebar', array('params' => $params, 'dateRange' => $dateRange)) ?>
	</div>		
	
</div>
<?php end_slot() ?>

<div id="a-blog-main" class="a-blog-main">
  <?php if ($sf_params->get('year')): ?>
  <h2><?php echo $sf_params->get('day') ?> <?php echo ($sf_params->get('month')) ? date('F', strtotime(date('Y').'-'.$sf_params->get('month').'-01')) : '' ?> <?php echo $sf_params->get('year') ?></h2>
  <ul class="a-controls a-blog-browser-controls">
    <li><?php echo link_to('Previous', 'aBlog/index?'.http_build_query($params['prev']), array('class' => 'a-btn icon a-arrow-left nobg', )) ?></li>
    <li><?php echo link_to('Next', 'aBlog/index?'.http_build_query($params['next']), array('class' => 'a-btn icon a-arrow-right nobg', )) ?></li>
  </ul>
  <?php endif ?>

  <?php if ($a_blog_posts->haveToPaginate()): ?>
 		<?php echo include_partial('aPager/pager', array('pager' => $a_blog_posts, 'pagerUrl' => url_for('aBlog/index?'. http_build_query($params['pagination'])))); ?>
  <?php endif ?>

  <?php foreach ($a_blog_posts->getResults() as $a_blog_post): ?>
  <?php echo include_partial('aBlog/post', array('a_blog_post' => $a_blog_post, 'excerpt' => 'true')); ?>
  <?php endforeach ?>

  <?php if ($a_blog_posts->haveToPaginate()): ?>
 		<?php echo include_partial('aPager/pager', array('pager' => $a_blog_posts, 'pagerUrl' => url_for('aBlog/index?'. http_build_query($params['pagination'])))); ?>
  <?php endif ?>
</div>