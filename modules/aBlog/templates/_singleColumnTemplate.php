<?php a_area('blog-body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlog-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 680)),
    'aButton' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 680)),
    'aSlideshow' => array("width" => 680, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 680)),
		'aVideo' => array('width' => 680), 
  ))
) ?>