<?php a_area('blog-body', array(
  'edit' => $edit, 'toolbar' => 'basic', 'slug' => 'aBlog/'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aSlideshow' => array("width" => 680, "flexHeight" => true, 'resizeType' => 's', 'constraints' => array('minimum-width' => 680)),
		'aVideo' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's'), 
		'aPDF' => array('width' => 680, 'flexHeight' => true, 'resizeType' => 's'),				
))) ?>

<?php include_partial('aBlog/addThis', array('aBlogPost' => $a_blog_post)) ?>
