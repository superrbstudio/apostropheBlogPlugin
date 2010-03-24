<?php use_helper('a') ?>
<div class="a-blog-post">
  <h2 class="a-blog-post-title">
    <?php echo link_to($a_blog_post->getTitle(), 'a_blog_post', $a_blog_post) ?>
  </h2>
  <ul class="a-blog-post-meta">
    <li class="date"><?php echo date('l F jS Y', strtotime($a_blog_post->getPublishedAt())) ?></li>
    <li class="author">Posted By: <?php echo $a_blog_post->getAuthor() ?></li>   
  </ul>

<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>

</div>


