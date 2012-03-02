<?php
// If app_aBlog_excerpts_show is true, this template will be used for every post on
// the index page of the blog
 ?>

<?php
  // Compatible with sf_escaping_strategy: true
  $a_blog_post = isset($a_blog_post) ? $sf_data->getRaw('a_blog_post') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
  $admin = ($sf_params->get('module') == 'aBlogAdmin') ? true : false;
  $excerptLength = (sfConfig::get('app_aBlog_excerpts_length')) ? sfConfig::get('app_aBlog_excerpts_length') : 30;
  $options = isset($options) ? $sf_data->getRaw('options') : null;
?>

<?php $catClass = ""; foreach ($a_blog_post->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>
<div class="a-blog-item post <?php echo $a_blog_post->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?> clearfix">

  <?php if (!$admin): ?>
    <h3 class="a-blog-item-title">
      <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
      <?php if ($a_blog_post['status'] == 'draft'): ?>
        <span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
      <?php endif ?>
    </h3>
    <?php include_partial('aBlog/meta', array('a_blog_post' => $a_blog_post)) ?>
  <?php endif ?>

  <?php if ($a_blog_post->hasMedia()): ?>
    <div class="a-blog-item-media">
    <?php include_component('aSlideshowSlot', 'slideshow', array(
      'items' => $a_blog_post->getMediaForArea('blog-body', 'image', 1),
      'id' => 'a-slideshow-blogitem-'.$a_blog_post['id'],
      'options' => array('width' => sfConfig::get('app_aBlog_media_width', 480))
      )) ?>
    </div>
  <?php endif ?>

  <div class="a-blog-item-content">

    <?php // Standard slot choices, minus aBlog and aEvent. Pass in the options to edit the right virtual page ?>
    <?php // Events cannot have blog slots and vice versa, otherwise they could recursively point to each other ?>

    <?php echo aHtml::simplify($a_blog_post->getRichTextForArea('blog-body', $excerptLength), array('allowedTags' => '<a><em><strong>'))  ?>

    <?php if (aHtml::limitWords($a_blog_post->getRichTextForArea('blog-body'), $excerptLength) !== $a_blog_post->getRichTextForArea('blog-body')): ?>
      <div class="a-blog-read-more">
        <?php echo link_to('Read More', 'a_blog_post', $a_blog_post, array('class' => 'a-blog-more')) ?>
      </div>
    <?php endif ?>

    <?php if (!$admin): ?>
      <?php include_partial('aBlog/tags', array('aBlogItem' => $a_blog_post)) ?>
      <?php include_partial('aBlog/addThis', array('aBlogItem' => $a_blog_post)) ?>
    <?php endif ?>

  </div>
</div>