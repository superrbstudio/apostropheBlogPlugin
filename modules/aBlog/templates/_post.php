<?php use_helper('a') ?>
<div class="a-blog-post">
  <h2 class="a-blog-post-title">
    <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
  </h2>
  <ul class="a-blog-post-meta">
    <li class="date"><?php echo date('l F jS Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
    <li class="author">Posted By: <?php echo $a_blog_post->getAuthor() ?></li>   
  </ul>
  
<?php slot('body_class') ?>a-home<?php end_slot() ?>

<?php // Subnav is removed for the home page template because it is redundant ?>
<?php slot('a-subnav', '') ?>

<?php a_area('aBlog_body_'.$a_blog_post['id'], array(
  'editable' => false,
  'global' => true,
  'allowed_types' => array('aRichText', 'aImage', 'aButton', 'aSlideshow', 'aVideo', 'aPDF'),
  'type_options' => array(
    'aRichText' => array('tool' => 'Main'),   
    'aImage' => array('width' => 720, 'flexHeight' => true, 'resizeType' => 's'),
    'aButton' => array('width' => 720, 'flexHeight' => true, 'resizeType' => 's'),
    'aSlideshow' => array("width" => 720, "flexHeight" => true, 'resizeType' => 's', ),
    'aPDF' => array('width' => 200, 'flexHeight' => true, 'resizeType' => 's'),   
  ))) ?>


