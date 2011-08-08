<?php slot('og-meta') ?>
<?php // og-meta is meta information for Facebook that gets read when something is shared with Add This (or anything else)  ?>
<meta property="og:title" content="<?php echo $aEvent->getTitle() ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo url_for('a_blog_post', $aBlogPost, true) ?>"/>
<?php $items = $aEvent->getMediaForArea('blog-body', 'image', 1) ?>
<?php if (count($items)): ?>
	<?php foreach ($items as $item): ?>
<meta property="og:image" content="<?php echo $item->getImgSrcUrl(400, false, 's', 'jpg', true) ?>"/>	
	<?php endforeach ?>
<?php endif ?>
<meta property="og:site_name" content="<?php echo sfContext::getInstance()->getResponse()->getTitle(); ?>"/>
<meta property="og:description" content="<?php echo $aEvent->getTextForArea('blog-body', 25) ?>"/>
<?php end_slot() ?>

<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php slot('a-subnav') ?>
	<div class="a-subnav-wrapper blog">
		<div class="a-subnav-inner">
	    <?php include_component('aBlog', 'sidebar', array('params' => $params, 'dateRange' => $dateRange, 'categories' => $blogCategories, 'reset' => true, 'noFeed' => true)) ?>
	  </div> 
	</div>
<?php end_slot() ?>

<?php echo include_partial('aBlog/post', array('a_blog_post' => $aBlogPost)) ?>

<?php if($aBlogPost['allow_comments']): ?><?php endif ?>