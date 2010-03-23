
  <th class="a-admin-text a-admin-list-th-title">
      <?php if ('title' == $sort[0]): ?>
      <?php echo jq_link_to_function(__('Title', array(), 'messages'), 'getFilters("title")') ?>
      <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
      <?php echo jq_link_to_function(__('Title', array(), 'messages'), 'getFilters("title")') ?>
    <?php endif; ?>
    <div id="a-admin-list-th-title-filter"></div>
  </th>

  <th class="a-admin-text a-admin-list-th-author">
  	<ul class="topnav">
  		<li><a href="#" class="a-blog-sort-label"><?php echo __('Author', array(), 'messages') ?></a>
        <div class="filternav">
					<hr/>
					<?php include_partial('aBlogAdmin/list_th_select', array('filters' => $filters, 'name' => 'author_id')) ?>
				</div>
      </li>
		</ul>
  </th>

  <th class="a-admin-text a-admin-list-th-editors">
  	<ul class="topnav">
  		<li><a href="#" class="a-blog-sort-label"><?php echo __('Editors', array(), 'messages') ?></a>
        <div class="filternav">
					<hr/>
					<?php include_partial('aBlogAdmin/list_th_select', array('filters' => $filters, 'name' => 'editors_list')) ?>
				</div>
			</li>
		</ul>
  </th>

  <th class="a-admin-text a-admin-list-th-tags">
  	<ul class="topnav a-blog-tags-sort">
  		<li><a href="#" class="a-blog-sort-label"><?php echo __('Tags', array(), 'messages') ?></a>
        <div class="filternav">
					<hr/>
					<?php include_partial('aBlogAdmin/list_th_tags', array('filters' => $filters)) ?>
				</div>
      </li>
    </ul>
  </th>

  <th class="a-admin-text a-admin-list-th-categories">
    <ul class="topnav">
  		<li><a href="#" class="a-blog-sort-label"><?php echo __('Categories', array(), 'messages') ?></a>
        <div class="filternav">
					<hr/>
					<?php include_partial('aBlogAdmin/list_th_select', array('filters' => $filters, 'name' => 'categories_list')) ?>
				</div>
      </li>
		</ul>
  </th>

  <th class="a-admin-enum a-admin-list-th-status">
    <ul class="topnav">
      <li><a href="#" class="a-blog-sort-label"><?php echo __('Status', array(), 'messages') ?></a>
        <div class="filternav">
					<hr/>
					<?php include_partial('aBlogAdmin/list_th_select', array('filters' => $filters, 'name' => 'status')) ?>
				</div>
      </li>
    </ul>
  </th>

  <th class="a-admin-date a-admin-list-th-published_at">
      <?php if ('published_at' == $sort[0]): ?>
      <?php echo jq_link_to_function(__('Date', array(), 'messages'), 'getFilters("published_at")') ?>
      <?php echo image_tag(sfConfig::get('app_aAdmin_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'a-admin'), 'title' => __($sort[1], array(), 'a-admin'))) ?>
    <?php else: ?>
      <?php echo jq_link_to_function(__('Date', array(), 'messages'), 'getFilters("published_at")') ?>
    <?php endif; ?>
    <div id="a-admin-list-th-published_at-filter"></div>
  </th>
