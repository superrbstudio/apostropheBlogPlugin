<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
  <?php if (isset($values['count'])): ?>

  <?php foreach ($aBlogPosts as $aBlogPost): ?>
    <div class="a-blog-post">
      <h2 class="a-blog-post-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h2>
        <ul class="a-blog-post-meta">
          <li class="date"><?php echo aDate::pretty($aBlogPost['published_at']) ?></li>
        </ul>
      <?php if (false): ?>
        <div class="a-blog-post-media">
    
        </div>
      <?php endif ?>

      <div class="a-blog-post-excerpt-container">
        <?php include_partial('aBlog/'.$aBlogPost['template'].'_slot', array('aBlogPost' => $aBlogPost)) ?>
        <div class="a-blog-read-more">
          <?php echo link_to('Read More', 'a_blog_post', $aBlogPost, array('class' => 'a-blog-more')) ?>
        </div>
      </div>
    </div>

  <?php endforeach ?>
<?php endif ?>
