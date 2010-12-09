<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogPost = isset($aBlogPost) ? $sf_data->getRaw('aBlogPost') : null;
?>

<?php if ((count($aBlogPost->getTags()) != 0)): ?>
<div class="a-blog-item-tags tags">
	<span class="a-blog-item-tags-label">Tags:</span>
		<?php $i=1; foreach ($aBlogPost->getTags() as $tag): ?>
			<?php echo link_to($tag, aUrl::addParams('aBlog/index?tag='.$tag, $tag)) ?><?php echo (($i < count($aBlogPost->getTags())) ? ', ':'')?>
		<?php $i++; endforeach ?>
</div>
<?php endif ?>
