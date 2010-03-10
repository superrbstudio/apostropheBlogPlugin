<ul id="quick-filter-categories" class="quick-filter" style="display:none;">
  <?php foreach($filters['categories_list']->getWidget()->getChoices() as $id => $choice): ?>
  <li><?php echo link_to($choice, 'aBlogAdmin/addFilter?filter_field=categories_list&filter_value='.$id, 'post=true') ?></li>
  <?php endforeach ?>
</ul>