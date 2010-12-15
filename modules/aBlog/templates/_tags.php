<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
	$type = $aBlogItem->getType();
?>

<?php if ((count($aBlogItem->getTags()) != 0)): ?>
<div class="a-blog-item-tags tags">
	<span class="a-blog-item-tags-label">Tags:</span>
		<?php $i=1; foreach ($aBlogItem->getTags() as $tag): ?>
			<?php echo link_to($tag, aUrl::addParams((($type == 'post') ? 'aBlog' : 'aEvent' ).'/index?tag='.$tag, $tag)) ?><?php echo (($i < count($aBlogItem->getTags())) ? ', ':'')?>
		<?php $i++; endforeach ?>
</div>
<?php endif ?>