<?php use_helper('a') ?>
<div class="a-blog-post">
  <h2 class="a-blog-post-title">
    <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
  </h2>
  <ul class="a-blog-post-meta">
    <li class="date"><?php echo date('l F jS Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
    <li class="author">Posted By: <?php echo $a_blog_post->getAuthor() ?></li>   
  </ul>

<?php a_area('body', array(
  'editable' => false, 'toolbar' => 'basic', 'slug' => 'aBlogPost-'.$a_blog_post['id'],
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),
    'aButton' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),
    'aSlideshow' => array("width" => 400, "flexHeight" => true, 'resizeType' => 's', ),
    'aPDF' => array('width' => 400, 'flexHeight' => true, 'resizeType' => 's'),   
  ))
) ?>
</div>


