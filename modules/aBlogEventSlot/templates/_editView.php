<?php if(count($a_blog_events)):?>
<select name="a_blog_event_id-<?php echo $id ?>" id="a_blog_event_id-<?php echo $id ?>">
  <?php foreach ($a_blog_events as $id => $a_blog_event):?>
    <option value="<?php echo $id ?>"><?php echo $a_blog_event ?></option>
  <?php endforeach?>
</select>

<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#a_blog_event_id-<?php echo $id ?>').addClass('aBlogSloteventSelect');
  });
</script>
<?php else:?>
You have no blog events.
<?php endif?>