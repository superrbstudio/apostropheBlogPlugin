<div class="a-blog-post-excerpt">
<?php echo $aBlogPost->getTextForArea('blog-body', $options['excerptLength']) ?>
</div>

<div class="a-blog-post-media">
<?php include_component('aSlideshowSlot', 'slideshow', array(
  'items' => $aBlogPost->getMediaForArea('blog-body', 'image', 1),
  'id' => 'test',
  'options' => $options['slideshowOptions']
  )) ?>
</div>

