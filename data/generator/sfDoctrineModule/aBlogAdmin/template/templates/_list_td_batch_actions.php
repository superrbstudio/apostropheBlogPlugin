<td class="a-admin-batch-actions first">
  <?php // Privilege checks here are redundant because we always filter by the posts we can edit. Thanks to Lasse Munk ?>
  <input type="checkbox" name="ids[]" value="[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]" class="a-admin-batch-checkbox a-checkbox" />
</td>
