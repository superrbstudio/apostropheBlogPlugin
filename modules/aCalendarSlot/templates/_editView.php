<select name="tags-<?php echo $id ?>[]" id="tags-<?php echo $id ?>" multiple="multiple">
  <?php foreach($tags as $key => $tag): ?>
  <option value="<?php echo $key ?>"><?php echo $tag ?></option> 
  <?php endforeach ?>
</select>

<script type="text/javascript" charset="utf-8">
	aMultipleSelect('#a-slot-form-<?php echo $id ?>', { });
</script>