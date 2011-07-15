<?php
  $page = aTools::getCurrentNonAdminPage();
	$a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
	$filterUrl = aUrl::addParams($page->getUrl(), array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author')));
?>

<span class="a-blog-item-meta-label"><?php echo __('Posted By:', array(), 'apostrophe') ?></span>
<?php if ($a_blog_post->getAuthor()): ?>
  <?php $author = $a_blog_post->getAuthor() ?>
	<?php echo link_to($author->getName() ? $author->getName() : $author, url_for(aUrl::addParams($filterUrl, array('author' => $author['username']))), array('a-link')) ?>
<?php endif ?>
