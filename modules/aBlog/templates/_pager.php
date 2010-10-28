<?php $pager = $sf_data->getRaw('pager') ?>
<?php $pagerUrl = $sf_data->getRaw('pagerUrl') ?>
<?php $max_per_page = $sf_data->getRaw('max_per_page') ?>

<?php $views = array(20, 50, 100) ?>
<?php include_partial('aPager/pager', array('pager' => $pager, 'pagerUrl' => $pagerUrl)) ?>
<ul class="a-ui a-controls a-media-footer-controls">
	<li class="a-media-footer-item-count"><?php echo $pager->count() ?> items</li>
	<li class="a-media-footer-separator a">|</li>
	<li class="a-media-footer-view-label">view</li>
	<?php foreach($views as $n): ?>
		<li class="a-media-footer-view-option"><?php echo link_to($n, aUrl::addParams($pagerUrl, array('max_per_page' => $n)), array('class' => 'a-btn lite alt'.(($max_per_page == $n)?' a-active':''))) ?></li>
	<?php endforeach ?>
	<li class="a-media-footer-separator b">|</li>
</ul>
