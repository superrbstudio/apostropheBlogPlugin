	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-title">
		  <?php if ('title' == $sort[0]): ?>
	    <?php echo jq_link_to_function(__('Title', array(), 'messages'), 'getFilters("title")') ?>
	    <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
	    <?php echo jq_link_to_function(__('Title', array(), 'messages'), 'getFilters("title")') ?>
    <?php endif; ?>
	  <div id="a-admin-list-th-title-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-author">
		  <?php echo jq_link_to_function(__('Author', array(), 'messages'), '$("#quick-filter-author").toggle()') ?>  
	  <div id="a-admin-list-th-author-filter"><?php include_partial('aBlogAdmin/list_th_author_list', array('filters' => $filters)) ?></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-editors">
		  <?php echo jq_link_to_function(__('Editors', array(), 'messages'), 'getFilters("editors")') ?>
	  <div id="a-admin-list-th-editors-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-tags">
		  <?php echo jq_link_to_function(__('Tags', array(), 'messages'), 'getFilters("tags")') ?>
	  <div id="a-admin-list-th-tags-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-text a-admin-list-th-categories">
		  <?php echo jq_link_to_function(__('Categories', array(), 'messages'), '$("#quick-filter-categories").toggle()') ?>
	  <div id="a-admin-list-th-categories-filter"><?php include_partial('aBlogAdmin/list_th_categories_list', array('filters' => $filters)) ?></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-enum a-admin-list-th-status">
		  <?php if ('status' == $sort[0]): ?>
	    <?php echo jq_link_to_function(__('Status', array(), 'messages'), 'getFilters("status")') ?>
	    <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
	    <?php echo jq_link_to_function(__('Status', array(), 'messages'), 'getFilters("status")') ?>
    <?php endif; ?>
	  <div id="a-admin-list-th-status-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-boolean a-admin-list-th-public">
		  <?php if ('public' == $sort[0]): ?>
	    <?php echo jq_link_to_function(__('Public', array(), 'messages'), 'getFilters("public")') ?>
	    <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
	    <?php echo jq_link_to_function(__('Public', array(), 'messages'), 'getFilters("public")') ?>
    <?php endif; ?>
	  <div id="a-admin-list-th-public-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
	<?php slot('a-admin.current-header') ?>
	<th class="a-admin-date a-admin-list-th-published_at">
		  <?php if ('published_at' == $sort[0]): ?>
	    <?php echo jq_link_to_function(__('Published at', array(), 'messages'), 'getFilters("published_at")') ?>
	    <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
	    <?php echo jq_link_to_function(__('Published at', array(), 'messages'), 'getFilters("published_at")') ?>
    <?php endif; ?>
	  <div id="a-admin-list-th-published_at-filter"></div>
	</th>
	<?php end_slot(); ?>

<?php include_slot('a-admin.current-header') ?>
