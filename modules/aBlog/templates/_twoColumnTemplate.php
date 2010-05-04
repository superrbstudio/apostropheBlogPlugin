<?php a_area('blog-body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlog/'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aSlideshow' => array("width" => 480, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 480)),
		'aVideo' => array('width' => 480), 
		'aPDF' => array('width' => 480, 'flexHeight' => true, 'resizeType' => 's'),		
))) ?>

<?php a_area('blog-sidebar', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlog/'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aSlideshow' => array("width" => 180, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 180)),
		'aVideo' => array('width' => 180), 
		'aPDF' => array('width' => 180, 'flexHeight' => true, 'resizeType' => 's'),				
))) ?>

<?php include_partial('aBlog/addThis') ?>