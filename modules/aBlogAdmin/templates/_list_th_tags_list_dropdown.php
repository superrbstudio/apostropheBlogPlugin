<?php
  // Compatible with sf_escaping_strategy: true
  $filters = isset($filters) ? $sf_data->getRaw('filters') : null;
  $class = isset($class) ? $sf_data->getRaw('class') : 'blog';
?>
<?php //Popular tags will go here eventually ?>
<?php $letter = ''; ?>
<?php $choices = $filters['tags_list']->getWidget()->getChoices() ?>
<?php if (sfConfig::get('app_aBlog_tagAutocomplete')): ?>
  <?php // javascript will replace the entire tag dropdown with this element ?>
  <th class="a-blog-tag-autocomplete-wrapper">
    <span class="a-blog-tag-autocomplete-label">Tags</span>
    <input type="text" id="a-blog-tag-autocomplete" />
    <span style="display: none" id="a-blog-tag-autocomplete-link">
      <?php echo link_to('hidden', 'a_' . $class . '_admin_addFilter', array('name' => 'tags_list', 'value' => 'DUMMY'), array('post' => true)) ?>
    </span>
  </th>
  <?php a_js_call('aBlog.tagAutocomplete(?)', array_values($choices)) ?>
<?php else: ?>
  <?php $n = 0; ?>
  <?php foreach($choices as $id => $choice): ?>
  <?php   if (!strlen($choice)) continue; ?>
  <?php   if(strtoupper($choice[0]) == $letter): ?>,<?php   else: ?>
  <?php     if(strtoupper($choice[0]) != 'A'): ?></span><?php endif ?>
  <?php   $letter = strtoupper($choice[0]) ?>
  <span<?php echo ($n == 0)? ' class="first"':'' ?><?php echo ($n == count($choices))? ' class="last"':'' ?>>
    <b><?php echo $letter ?></b>
  <?php endif ?>
  <?php echo link_to($choice, 'a_' . $class . '_admin_addFilter', array('name' => 'tags_list', 'value' => $id), array('post' => true)) ?>
  <?php $n++; endforeach ?>
<?php endif ?>
