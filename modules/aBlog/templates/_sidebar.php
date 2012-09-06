<?php
  // Compatible with sf_escaping_strategy: true
  $categories = isset($categories) ? $sf_data->getRaw('categories') : null;
  $authors = isset($authors) ? $sf_data->getRaw('authors') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $noFeed = isset($noFeed) ? $sf_data->getRaw('noFeed') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $tagsByPopularity = isset($tagsByPopularity) ? $sf_data->getRaw('tagsByPopularity') : null;
  $tagsByName = isset($tagsByName) ? $sf_data->getRaw('tagsByName') : null;
	$url = isset($url) ? $sf_data->getRaw('url') : null;
	$searchLabel = isset($searchLabel) ? $sf_data->getRaw('searchLabel') : null;
	$newLabel = isset($newLabel) ? $sf_data->getRaw('newLabel') : null;
	$adminModule = isset($adminModule) ? $sf_data->getRaw('adminModule') : null;
  $calendar = isset($calendar) ? $sf_data->getRaw('calendar') : null;
  $tag = (!is_null($sf_params->get('tag'))) ? $sf_params->get('tag') : null;
  $layoutOptions = isset($layoutOptions) ? $sf_data->getRaw('layoutOptions') : null;
  // Handy flags to shut these features off; eliminates some common override cases
  $showDates = isset($showDates) ? $sf_data->getRaw('showDates') : true;
  $showCategories = isset($showCategories) ? $sf_data->getRaw('showCategories') : true;
  $info = isset($info) ? $sf_data->getRaw('info') : array();
?>

<?php $selected = array('icon','a-selected','alt','icon-right'); // Class names for selected filters ?>

<?php // Categories in the subnav are redundant when there is only one possible on this page. ?>
<?php // If I've missed something and this has to be reverted, FM will need an override ?>
<?php if (count($categories) === 1): ?>
  <?php $showCategories = false ?>
<?php endif ?>

<?php // This partial sets up the sidebar as a series of Symfony slots, then ?>
<?php // includes aBlog/sidebarLayout which outputs them. You can override that partial ?>
<?php // to alter the sequence and impose your own elements without losing out on ?>
<?php // bug fixes we make later (although you'll have to watch for entirely new slots). ?>

<?php slot('a_blog_sidebar_new_post') ?>

<?php if (aBlogItemTable::userCanPost()): ?>
	<div class="a-ui clearfix a-subnav-section a-sidebar-button-wrapper">
	  <?php echo a_js_button($newLabel, array('big', 'a-add', 'a-blog-new-post-button', 'a-sidebar-button'), 'a-blog-new-post-button') ?>
    <div class="a-ui a-options a-blog-admin-new-ajax dropshadow">
      <?php include_component($newModule, $newComponent) ?>
    </div>
	</div>
<?php endif ?>

<?php end_slot('a_blog_sidebar_new_post') ?>

<?php // Do not jam year month and day into non-date filters when departing from an individual post ?>
<?php if ($sf_params->get('action') === 'show'): ?>
  <?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author'))) ?>
<?php else: ?>
  <?php $filterUrl = aUrl::addParams($url, array('tag' => $sf_params->get('tag'), 'cat' => $sf_params->get('cat'), 'year' => $sf_params->get('year'), 'month' => $sf_params->get('month'), 'day' => $sf_params->get('day'), 'q' => $sf_params->get('q'), 'author' => $sf_params->get('author'))) ?>
<?php endif ?>

<?php foreach ($params['extraFilterCriteria'] as $efc): ?>
  <?php $filterUrl = aUrl::addParams($filterUrl, array($efc['urlParameter'] => $sf_params->get($efc['urlParameter']))) ?>
<?php endforeach ?>

<?php slot('a_blog_sidebar_search') ?>

<div class="a-subnav-section search">

  <div class="a-ui a-search a-search-sidebar blog">
    <form action="<?php echo url_for(aUrl::addParams($filterUrl, array('q' => ''))) ?>" method="get">
  		<div class="a-form-row"> <?php // div is for page validation ?>
  			<label for="a-search-blog-field" style="display:none;">Search</label><?php // label for accessibility ?>
  			<input type="text" name="q" value="<?php echo htmlspecialchars($sf_params->get('q', null, ESC_RAW)) ?>" class="a-search-field" id="a-search-blog-field"/>
  			<input type="image" src="<?php echo image_path('/apostrophePlugin/images/a-special-blank.gif') ?>" class="submit a-search-submit" value="Search Pages" alt="Search" title="Search"/>
  		</div>
    </form>
  </div>

</div>

<?php end_slot('a_blog_sidebar_search') ?>

<?php slot('a_blog_sidebar_dates') ?>

<?php if ($showDates): ?>
  <?php if (isset($calendar) && $calendar): ?>
    <hr class="a-hr" />
    <?php include_partial('aEvent/calendar', array('calendar' => $calendar)) ?>
  <?php else: ?>
    <hr class="a-hr" />
    <div class='a-subnav-section range'>
      <h4><?php echo a_('Browse by') ?></h4>
      <div class="a-filter-options blog clearfix">
        <div class="a-filter-option">
    			<?php $selected_day = ($dateRange == 'day') ? $selected : array() ?>
    			<?php echo a_button('Day', url_for($url . '?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day'])), array_merge(array('a-link'),$selected_day)) ?>
    		</div>
        <div class="a-filter-option">
    			<?php $selected_month = ($dateRange == 'month') ? $selected : array() ?>
    			<?php echo a_button('Month', url_for($url . '?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month'])), array_merge(array('a-link'),$selected_month)) ?>
    		</div>
        <div class="a-filter-option">
    			<?php $selected_year = ($dateRange == 'year') ? $selected : array() ?>
    			<?php echo a_button('Year', url_for($url . '?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year'])), array_merge(array('a-link'),$selected_year)) ?>
    		</div>
      </div>
    </div>
  <?php endif ?>
<?php endif ?>

<?php end_slot('a_blog_sidebar_dates') ?>

<?php if ($showCategories): ?>
  <?php slot('a_blog_sidebar_categories') ?>

  <hr class="a-hr" />
  <div class="a-subnav-section categories">
  <h4><?php echo a_(sfConfig::get('app_aBlog_categories_label', 'Categories')) ?></h4>
  <div class="a-filter-options blog clearfix">
    <?php foreach ($categories as $category): ?>
      <?php $isSelected = ($category['slug'] === $sf_params->get('cat')) ?>
      <?php // Sometimes it is helpful to have this on the div ?>
      <div class="a-filter-option <?php echo $isSelected ? 'a-filter-current' : '' ?>">
  			<?php $selected_category = $isSelected ? $selected : array() ?>
  			<?php echo a_button($category['name'], url_for(aUrl::addParams($filterUrl, array('cat' => ($sf_params->get('cat') === $category['slug']) ? '' : $category['slug']))), array_merge(array('a-link'),$selected_category)) ?>
  		</div>
    <?php endforeach ?>
  </div>
  </div>

  <?php end_slot('a_blog_sidebar_categories') ?>
<?php endif ?>

<?php slot('a_blog_sidebar_tags') ?>

<?php if(count($tagsByName)): ?>
<hr class="a-hr" />
<div class="a-subnav-section tags">

	<?php if (isset($tag)): ?>
	<div class="a-tag-sidebar-selected-tag clearfix">
		<h4 class="a-tag-sidebar-title selected-tag"><?php echo a_('Selected Tag') ?></h4>
		<?php echo a_button($tag, url_for(aUrl::addParams($filterUrl, array('tag' => ''))), array('a-link','icon','a-selected')) ?>
	</div>
	<?php endif ?>

	<h4 class="a-tag-sidebar-title popular"><?php echo a_('Popular Tags') ?></h4>
	<ul class="a-ui a-tag-sidebar-list popular">
		<?php $n=1; foreach ($tagsByPopularity as $tagInfo): ?>
		  <li <?php echo ($n == count($tagsByPopularity) ? 'class="last"':'') ?>>
				<?php echo a_button('<span class="a-tag-count icon">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag','icon','no-icon','icon-right')) ?>
			</li>
		<?php $n++; endforeach ?>
	</ul>

	<h4 class="a-tag-sidebar-title all-tags"><?php echo a_('All Tags') ?> <span class="a-tag-sidebar-tag-count"><?php echo count($tagsByName) ?></span></h4>
	<ul class="a-ui a-tag-sidebar-list all-tags">
		<?php $n=1; foreach ($tagsByName as $tagInfo): ?>
		  <li <?php echo ($n == count($tagsByName) ? 'class="last"':'') ?>>
				<?php echo a_button('<span class="a-tag-count icon">'.$tagInfo['t_count'].'</span>'.$tagInfo['name'], url_for(aUrl::addParams($filterUrl, array('tag' => $tagInfo['name']))), array('a-link','a-tag','icon','no-icon','icon-right')) ?>
			</li>
		<?php $n++; endforeach ?>
	</ul>

</div>
<?php endif ?>

<?php end_slot('a_blog_sidebar_tags') ?>

<?php slot('a_blog_sidebar_authors') ?>

<?php if (count($authors) > 1): ?>
<hr class="a-hr" />
<div class="a-subnav-section authors">
  <h4 class="filter-label<?php echo ($sf_params->get('author')) ? ' open' : '' ?>"><?php echo a_('Authors') ?></h4>
  <div class="a-filter-options blog clearfix<?php echo ($sf_params->get('author')) ? ' open' : '' ?>">
	  <?php foreach ($authors as $author): ?>
			<?php $selected_author = ($author['username'] === $sf_params->get('author')) ? $selected : array() ?>
	    <div class="a-filter-option">
				<?php echo a_button($author->getName() ? $author->getName() : $author, url_for(aUrl::addParams($filterUrl, array('author' => ($sf_params->get('author') === $author['username']) ? '' : $author['username']))), array_merge(array('a-link'),$selected_author)) ?>
			</div>
	  <?php endforeach ?>
  </div>
</div>
<?php endif ?>

<?php end_slot('a_blog_sidebar_authors') ?>

<?php // Add sections in the sidebar for custom filter criteria ?>
<?php if (isset($params['extraFilterCriteria'])): ?>
  <?php foreach ($params['extraFilterCriteria'] as $efc): ?>
    <?php slot('a_blog_sidebar_' . $efc['arrayKey']) ?>
      <?php $items = array() ?>
      <?php foreach ($info[$efc['arrayKey']] as $row): ?>
        <?php if ($sf_params->get($efc['urlParameter']) == $row[$efc['urlColumn']]): ?>
          <?php // Take it out ?>
          <?php $row['filterUrl'] = aUrl::addParams($filterUrl, array($efc['urlParameter'] => '')) ?>
        <?php else: ?>
          <?php $row['filterUrl'] = aUrl::addParams($filterUrl, array($efc['urlParameter'] => $row[$efc['urlColumn']])) ?>
        <?php endif ?>
        <?php $items[] = $row ?>
      <?php endforeach ?>
      <?php if (isset($efc['sidebarComponent'])): ?>
        <?php include_component($efc['sidebarComponent'][0], $efc['sidebarComponent'][1], array('items' => $items)) ?>
      <?php else: ?>
        <?php include_partial($efc['sidebarPartial'], array('items' => $items)) ?>
      <?php endif ?>
    <?php end_slot() ?>
  <?php endforeach ?>
<?php endif ?>

<?php slot('a_blog_sidebar_feeds') ?>

<?php if(!isset($noFeed)): ?>
	<hr class="a-hr" />
	<ul class="a-ui a-controls stacked">
  <?php $full = $url . '?feed=rss' ?>
  <?php // Everything not date-related. A date-restricted RSS feed is a bit of a contradiction ?>
  <?php $filtered = aUrl::addParams($filterUrl, array('feed' => 'rss', 'year' => '', 'month' => '', 'day' => '')) ?>
  <?php if ($full === $filtered): ?>
    <li><?php echo a_button(a_('RSS Feed'), url_for($full), array('a-link','icon','a-rss-feed', 'no-bg','color')) ?></li>
  <?php else: ?>
    <li><?php echo a_button(a_('Full Feed'), url_for($full), array('a-link','icon','a-rss-feed','no-bg', 'color')) ?></li>
    <li><?php echo a_button(a_('Filtered Feed'), url_for($filtered), array('a-link','icon','a-rss-feed','no-bg', 'color')) ?></li>
  <?php endif ?>
	</ul>
<?php endif ?>

<?php end_slot('a_blog_sidebar_feeds') ?>

<?php a_js_call('aBlog.sidebarEnhancements(?)', array()) ?>
<?php a_js_call('apostrophe.selfLabel(?)', array('selector' => '#a-search-blog-field', 'title' => $searchLabel, 'focus' => false )) ?>

<?php include_partial('aBlog/sidebarLayout', array('options' => $layoutOptions)) ?>
