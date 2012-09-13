<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
	$type = $aBlogItem->getType();
?>

<?php include_partial('aBlog/beforeTags', array('aBlogItem' => $aBlogItem)) ?>

<?php if (sfConfig::get('app_aBlog_showCategoriesForPosts')): ?>
  <?php // At long last we can safely link to the categories for a blog post ?>
  <?php // or event. This code checks whether each category has an acceptable ?>
  <?php // engine page before linking ?>
  <?php if ((count($aBlogItem->getCategories()) != 0)): ?>
    <div class="a-blog-item-tags tags">
    	<span class="a-blog-item-tags-label">Categories:</span>
    		<?php $i=1; foreach ($aBlogItem->getCategories() as $cat): ?>
          <?php list($engineSlug, $engineCategories) = aEngineTools::getBestEngineForCategories($aBlogItem->getTable(), array($cat->name), array('mustMatch' => true)) ?>
          <?php if ($engineSlug): ?>
            <?php aRouteTools::pushTargetEngineSlug($engineSlug, $aBlogItem->Page->engine) ?>
            <?php // If the engine page we're linking to has only one ?>
            <?php // explicit category, we can avoid a redundant parameter ?>
            <?php // in the URL ?>
            <?php if (count($engineCategories) !== 1): ?>
              <?php $args = array('cat' => $cat->slug) ?>
            <?php else: ?>
              <?php $args = array() ?>
            <?php endif ?>
      			<?php echo link_to($cat->name, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', $args)) ?>
            <?php aRouteTools::popTargetEngine($aBlogItem->Page->engine) ?>
          <?php else: ?>
            <span class="a-unlinked-category"><?php echo $cat->name ?></span>
          <?php endif ?>
          <?php echo (($i < count($aBlogItem->getCategories())) ? ', ' : '') ?>
    		<?php $i++; endforeach ?>
    </div>
  <?php endif ?>
<?php endif ?>

<?php if ((count($aBlogItem->getTags()) != 0)): ?>
<div class="a-blog-item-tags tags">
	<span class="a-blog-item-tags-label">Tags:</span>
		<?php $i=1; foreach ($aBlogItem->getTags() as $tag): ?>
			<?php echo link_to($tag, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index', array('tag' => $tag))) ?><?php echo (($i < count($aBlogItem->getTags())) ? ', ':'')?>
		<?php $i++; endforeach ?>
</div>
<?php endif ?>

<?php include_partial('aBlog/afterTags', array('aBlogItem' => $aBlogItem)) ?>
