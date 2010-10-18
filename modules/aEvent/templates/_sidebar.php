<?php
  // Compatible with sf_escaping_strategy: true
  $categories = isset($categories) ? $sf_data->getRaw('categories') : null;
  $n = isset($n) ? $sf_data->getRaw('n') : null;
  $params = isset($params) ? $sf_data->getRaw('params') : null;
  $popular = isset($popular) ? $sf_data->getRaw('popular') : null;
  $tag = isset($tag) ? $sf_data->getRaw('tag') : null;
  $tags = isset($tags) ? $sf_data->getRaw('tags') : null;
?>

<?php if ($calendar): ?>
	<?php include_partial('aEvent/calendar', array('calendar' => $calendar)) ?>
	<hr />
<?php endif ?>

<?php if(count($categories)): ?>
<div class="a-subnav-section categories">
  <h4>Categories</h4>
  <div class="a-filter-options blog">
	  <?php foreach ($categories as $category): ?>
	    <div class="a-filter-option"><?php echo link_to($category, ($sf_params->get('cat') == $category->getName()) ? 'aEvent/index' : 'aEvent/index?cat='.$category->getName(), array('class' => ($category->getName() == $sf_params->get('cat')) ? 'selected' : '')) ?></div>
	  <?php endforeach ?>
  </div>	
</div>

<hr />
<?php endif ?>

<?php if (!$calendar): ?>
<div class='a-subnav-section range'>
  <h4>Browse by</h4>
  <div class="a-filter-options blog">
    <div class="a-filter-option"><?php echo link_to('Day', 'aEvent/index?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day']), array('class' => ($dateRange == 'day') ? 'selected' : '')) ?></div>
    <div class="a-filter-option"><?php echo link_to('Month', 'aEvent/index?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month']), array('class' => ($dateRange == 'month') ? 'selected' : '')) ?></div>
    <div class="a-filter-option"><?php echo link_to('Year', 'aEvent/index?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year']), array('class' => ($dateRange == 'year') ? 'selected' : '')) ?></div>
  </div>
</div>
<hr />
<?php endif ?>

<?php if(count($tags)): ?>
<div class="a-subnav-section tags">  

	<?php if (isset($tag)): ?>
	<h4 class="a-tag-sidebar-title selected-tag">Selected Tag</h4>  
	<div class="a-blog-selected-tag">
		<div class="selected"><?php echo link_to($tag, 'aEvent/index', $params['tag'], array('class' => 'selected', )) ?></div>
  </div>
	<?php endif ?>
  
  
	<h4 class="a-tag-sidebar-title popular">Popular Tags</h4>  			
	<ul class="a-ui a-tag-sidebar-list popular">
		<?php $n=1; foreach ($popular as $tag => $count): ?>
	  <li <?php echo ($n == count($popular) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, 'aEvent/index?tag='.$tag, $params['tag']) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>

	<br class="c"/>
	<h4 class="a-tag-sidebar-title all-tags">All Tags <span class="a-tag-sidebar-tag-count"><?php echo count($tags) ?></span></h4>
	<ul class="a-ui a-tag-sidebar-list all-tags">
		<?php $n=1; foreach ($tags as $tag => $count): ?>
	  <li <?php echo ($n == count($tag) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, 'aEvent/index?tag='.$tag) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>
	
</div>
<?php endif ?>

<?php if(!isset($noFeed)): ?>
<hr />
<h5><?php echo link_to(__('RSS Feed &ndash; Full', array(), 'apostrophe'), 'aEvent/index?feed=rss') ?></h5>
<h5><?php echo link_to(__('RSS Feed &ndash; Filtered', array(), 'apostrophe'), aUrl::addParams('aEvent/index?feed=rss', $params['tag'], $params['cat'])) ?></h5>
<?php endif ?>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$('.a-tag-sidebar-title.all-tags').click(function(){
		$('.a-tag-sidebar-list.all-tags').slideToggle();
		$(this).toggleClass('open');
	});
	
	$('.a-tag-sidebar-title.all-tags').hover(function(){
		$(this).toggleClass('over');
	},
	function(){
		$(this).toggleClass('over');		
	});	
	
	$('a.selected').prepend('<span class="close"></span>');
});	
 //]]>
</script>