<?php
  // Compatible with sf_escaping_strategy: true
  $aBlogItem = isset($aBlogItem) ? $sf_data->getRaw('aBlogItem') : null;
	$type = $aBlogItem->getType();
?>

<?php $addthis_identifier = sfConfig::get('app_aBlog_add_this') ?>

<?php if (strlen($addthis_identifier)): ?>
  <?php // Recognize the new pubids and use a different keyword ?>
  <?php if (preg_match('/^[a-z][a-z]\-[0-9a-f]+$/', $addthis_identifier)): ?>
    <?php $keyword = 'pubid' ?>
  <?php else: ?>
    <?php // Old school addthis username ?>
    <?php $keyword = 'username' ?>
  <?php endif ?>

	<!-- AddThis Button BEGIN -->
	<div class="addthis_toolbox addthis_default_style"
	addthis:url="<?php echo url_for( (($type == 'post') ? 'a_blog_post':'a_event_post' ), $aBlogItem, true) ?>"
	addthis:title="<?php echo $aBlogItem['title'] ?>">
		<a href="http://addthis.com/bookmark.php?v=250&amp;<?php echo $keyword ?>=<?php echo $addthis_identifier ?>" class="addthis_button_compact">Share</a>
		<span class="addthis_separator">|</span>
		<a class="addthis_button_facebook"></a>
    <a class="addthis_button_twitter"></a>
		<a class="addthis_button_google"></a>
	</div>
	<!-- AddThis Button END -->
	<?php use_javascript('http://s7.addthis.com/js/250/addthis_widget.js#' . $keyword . '=' . $addthis_identifier) ?>
<?php endif ?>

