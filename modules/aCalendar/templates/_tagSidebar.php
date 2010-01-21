<div class="a-blog-categories">
  <h4>Categories</h4>
  <ul class="a-blog-filter-options">
  <?php foreach ($categories as $category): ?>
    <li><?php echo link_to($category, aUrl::addParams(($sf_params->get('cat') == $category->getSlug()) ? 'aCalendar/index' : 'aCalendar/index?cat='.$category->getSlug(), $params['cat']), array(
      'class' => ($category->getSlug() == $sf_params->get('cat')) ? 'selected' : '', 
    )) ?></li>
  <?php endforeach ?>
  </ul>	
</div>

<hr />

<div class="a-blog-filter">
  <h4>Browse by</h4>
  <ul class="a-blog-filter-options">
    <li><?php echo link_to('Day', 'aCalendar/index?'.http_build_query(($dateRange == 'day') ? $params['nodate'] : $params['day']), array('class' => ($dateRange == 'day') ? 'selected' : '')) ?></li>
    <li><?php echo link_to('Month', 'aCalendar/index?'.http_build_query(($dateRange == 'month') ? $params['nodate'] : $params['month']), array('class' => ($dateRange == 'month') ? 'selected' : '')) ?></li>
    <li><?php echo link_to('Year', 'aCalendar/index?'.http_build_query(($dateRange == 'year') ? $params['nodate'] : $params['year']), array('class' => ($dateRange == 'year') ? 'selected' : '')) ?></li>
  </ul>
</div>

<hr />

<div class="a-blog-tags">  

	<?php if (isset($tag)): ?>
	<h4 class="a-tag-sidebar-title selected-tag">Selected Tag</h4>  
	<ul class="a-blog-selected-tag">
		<li><?php #echo $tag ?><?php echo link_to($tag, aUrl::addParams('aCalendar/index', $params['tag']), array('class' => 'selected', )) ?></li>
	</ul>
	<?php endif ?>


	<h4 class="a-tag-sidebar-title popular">Popular Tags</h4>  			
	<ul class="a-tag-sidebar-list popular">
		<?php $n=1; foreach ($popular as $tag => $count): ?>
	  <li <?php echo ($n == count($popular) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, aUrl::addParams('aCalendar/index?tag='.$tag, $params['tag'])) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>

	<br class="c"/>
	<h4 class="a-tag-sidebar-title all-tags">All Tags <span class="a-tag-sidebar-tag-count"><?php echo count($tags) ?></span></h4>
	<ul class="a-tag-sidebar-list all-tags">
		<?php $n=1; foreach ($tags as $tag => $count): ?>
	  <li <?php echo ($n == count($tag) ? 'class="last"':'') ?>>
			<span class="a-tag-sidebar-tag"><?php echo link_to($tag, aUrl::addParams('aCalendar/index?tag='.$tag, $params['tag'])) ?></span>
			<span class="a-tag-sidebar-tag-count"><?php echo $count ?></span>
		</li>
		<?php $n++; endforeach ?>
	</ul>
	
</div>

<script type="text/javascript">
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
	
	$('a.selected').prepend('<span class="close"></span>')
});	
</script>