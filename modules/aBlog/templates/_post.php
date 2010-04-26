<?php use_helper('a', 'I18N') ?>
<?php $catClass = ""; foreach ($a_blog_post->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-post <?php echo $a_blog_post->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">
  <h2 class="a-blog-post-title">
    <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
  </h2>
  <ul class="a-blog-post-meta">
    <li class="date"><?php echo date('l F jS Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
    <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $a_blog_post->getAuthor() ?></li>   
  </ul>
<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>
</div>


