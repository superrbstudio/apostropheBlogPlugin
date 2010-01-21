<div id="a-subnav" class="blog">
	<div id="a-subnav-top" class="a-subnav-top"></div>
	<div class="a-subnav-wrapper">
		<?php include_component('aBlogSlot', 'tagSidebar', array('params' => $params, 'dateRange' => $dateRange)) ?>
	</div>		
	<div id="a-subnav-bottom" class="a-subnav-bottom"></div>
</div>

<div class="a-blog-main">
  <?php if ($a_blog_post): ?>
    <?php echo include_partial('aBlogSlot/post', array('a_blog_post' => $a_blog_post)); ?>
  <?php else: ?>
    <?php echo include_partial('aBlogSlot/list', array('a_blog_posts' => $a_blog_posts, 'params' => $params)); ?>
  <?php endif ?>
</div>