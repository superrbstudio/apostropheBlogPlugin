<?php a_area('blog-body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 480, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 480)),
    'aButton' => array('width' => 480, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 480)),
    'aSlideshow' => array("width" => 480, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 480)),
		'aVideo' => array('width' => 480), 
  ))
) ?>

<?php a_area('blog-sidebar', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 180, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 180)),
    'aButton' => array('width' => 180, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 180)),
    'aSlideshow' => array("width" => 180, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 180)),
		'aVideo' => array('width' => 180), 
  ))
) ?>
