<?php
  $page = aTools::getCurrentNonAdminPage();
	$a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
	$filterUrl = aUrl::addParams($page->getUrl(), array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author')));
?>

<?php use_helper('a') ?>

<span class="a-blog-item-meta-label"><?php echo a_(sfConfig::get('app_aBlog_author_label', 'Posted By:')) ?></span>
<?php if ($a_blog_post->getAuthorId()): ?>
  <?php $author = $a_blog_post->getAuthor() ?>
  <?php if (sfConfig::get('app_aBlog_link_author', false)): ?>
    <?php echo link_to($author->getName() ? aHtml::entities($author->getName()) : aHtml::entities($author), '@a_blog_author?' . http_build_query(array('author' => $author->username)), array('class' => 'a-link')) ?>
	<?php else: ?>
	  <?php echo $author->getName() ? aHtml::entities($author->getName()) : aHtml::entities($author) ?>
	<?php endif ?>
<?php endif ?>
