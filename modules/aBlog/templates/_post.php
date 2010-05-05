<?php use_helper('a', 'I18N') ?>
<?php $catClass = ""; foreach ($a_blog_post->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item post <?php echo $a_blog_post->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?>">
  <h3 class="a-blog-item-title">
    <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
  </h3>
  <ul class="a-blog-item-meta">
    <li class="date">hello <?php echo aDate::pretty($a_blog_post['published_at']); ?></li>
    <li class="author"><?php echo __('Posted By:', array(), 'apostrophe_blog') ?> <?php echo $a_blog_post->getAuthor() ?></li>   
  </ul>
<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post, 'edit' => false)) ?>
</div>


