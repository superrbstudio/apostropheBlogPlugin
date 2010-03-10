<ul id="quick-filter-author" class="quick-filter" style="display:none;">
  <?php foreach($filters['author_id']->getWidget()->getChoices() as $id => $choice): ?>
  <?php if($choice != ''): ?>
    <li><?php echo link_to($choice, 'aBlogAdmin/addFilter?filter_field=author_id&filter_value='.$id, 'post=true') ?></li>
  <?php endif ?>
  <?php endforeach ?>
</ul>