<?php if(count($a_blog_posts)):?>
<select name="a_blog_post_id-<?php echo $id ?>" id="a_blog_post_id-<?php echo $id ?>">
  <?php foreach ($a_blog_posts as $id => $a_blog_post):?>
    <option value="<?php echo $id ?>"><?php echo $a_blog_post ?></option>
  <?php endforeach?>
</select>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#a_blog_post_id-<?php echo $id ?>').addClass('aBlogPostSlotSelect');
  });
</script>
<?php else:?>
You have no blog posts.
<?php endif?>