<div class="a-blog-post-excerpt">
<?php echo $aBlogPost->getTextForArea('blog-body', $options['excerptLength']) ?>
</div>

<?php if($options['maxImages'] > 0): ?>
<div class="a-blog-post-media">
<?php include_component('aSlideshowSlot', 'slideshow', array(
  'items' => $aBlogPost->getMediaForArea('blog-body', 'image', $options['maxImages']),
  'id' => 'test',
  'options' => $options['slideshowOptions']
  )) ?>
</div>
<?php endif ?>
