<?php if ($a_blog_event->getAttachedMedia()): ?>
	<?php if (in_array('aSlideshowSlot', sfConfig::get('sf_enabled_modules'))): ?>
		<?php include_component('aSlideshowSlot', 'slideshow', array(
			'items' => $a_blog_event->getAttachedMedia(),
			'id' => $a_blog_event->getId(),
			'options' => array('width' => 80, 'height' => 60, 'resizeType' => 'c', 'arrows' => false )
		)) ?>
	<?php else: ?>
	  <ul>
	  <?php foreach ($a_blog_event->getAttachedMedia() as $media): ?>
	    <li><?php echo image_tag(str_replace(
	      array("_WIDTH_", "_HEIGHT_", "_c-OR-s_", "_FORMAT_"),
	      array('120', '90', 'c', 'jpg',),
	      $media->image
	    )) ?></li>
	  <?php endforeach ?>
	  </ul>
  <?php endif ?>
<?php endif ?>