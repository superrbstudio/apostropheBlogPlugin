<?php
  // Compatible with sf_escaping_strategy: true
  $a_event = isset($a_event) ? $sf_data->getRaw('a_event') : null;
  $edit = isset($edit) ? $sf_data->getRaw('edit') : null;
  $admin = ($sf_params->get('module') == 'aEventAdmin') ? true : false;
  $excerptLength = (sfConfig::get('app_aEvents_excerpts_length')) ? sfConfig::get('app_aEvents_excerpts_length') : 30;
?>

<?php $catClass = ""; foreach ($a_event->getCategories() as $category): ?><?php $catClass .= " category-".aTools::slugify($category); ?><?php endforeach ?>

<div class="a-blog-item event <?php echo $a_event->getTemplate() ?><?php echo ($catClass != '')? $catClass:'' ?> clearfix">
  <?php if (!$admin): ?>
    <h3 class="a-blog-item-title">
      <?php echo link_to($a_event->getTitle(), 'a_event_post', $a_event) ?>
      <?php if ($a_event['status'] == 'draft'): ?>
        <span class="a-blog-item-status">&ndash; <?php echo a_('Draft') ?></span>
      <?php endif ?>
    </h3>
    <?php include_partial('aEvent/meta', array('aEvent' => $a_event)) ?>
  <?php endif ?>
  <div class="a-blog-item-content">
    <?php echo aHtml::simplify($a_event->getRichTextForArea('blog-body', $excerptLength), array('allowedTags' => '<a><em><strong>'))  ?>

    <?php if (aHtml::limitWords($a_event->getRichTextForArea('blog-body'), $excerptLength) !== $a_event->getRichTextForArea('blog-body')): ?>
      <div class="a-blog-read-more">
        <?php echo link_to('Read More', 'a_event_post', $a_event, array('class' => 'a-blog-more')) ?>
      </div>
    <?php endif ?>
  </div>
  <?php slot('disqus_needed', 1) ?>

</div>