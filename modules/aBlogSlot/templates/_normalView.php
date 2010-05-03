<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
  <?php if (isset($values['count'])): ?>

  <?php foreach ($aBlogPosts as $aBlogPost): ?>
    <div class="a-blog-item post<?php ($hasMedia)? ' has-media':''; ?>">
      <h3 class="a-blog-item-title"><?php echo link_to($aBlogPost['title'], 'a_blog_post', $aBlogPost) ?></h3>
        <ul class="a-blog-item-meta">
          <li class="date"><?php echo aDate::pretty($aBlogPost['published_at']) ?></li>
        </ul>

      <div class="a-blog-item-excerpt-container">
        <?php include_partial('aBlog/'.$aBlogPost['template'].'_slot', array('aBlogPost' => $aBlogPost, 'options' => $options)) ?>
        <div class="a-blog-read-more">
          <?php echo link_to('Read More', 'a_blog_post', $aBlogPost, array('class' => 'a-blog-more')) ?>
        </div>
      </div>
    </div>

  <?php endforeach ?>
<?php endif ?>
