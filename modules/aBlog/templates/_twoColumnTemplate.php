<div style="float:left;width:500px;padding-right:10px">
<?php a_area('body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 500, 'flexHeight' => true, 'resizeType' => 's'),
    'aButton' => array('width' => 500, 'flexHeight' => true, 'resizeType' => 's'),
    'aSlideshow' => array("width" => 500, "flexHeight" => true, 'resizeType' => 's', )
  ))
) ?>
</div>

<div style="float:left;width:200px;">
<?php a_area('sidebar', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 200, 'flexHeight' => true, 'resizeType' => 's'),
    'aButton' => array('width' => 200, 'flexHeight' => true, 'resizeType' => 's'),
    'aSlideshow' => array("width" => 200, "flexHeight" => true, 'resizeType' => 's', )
  ))
) ?>
</div>