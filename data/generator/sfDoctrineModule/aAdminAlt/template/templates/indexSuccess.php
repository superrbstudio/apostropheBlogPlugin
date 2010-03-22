[?php use_helper('I18N', 'Date', 'jQuery') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<script type="text/javascript">
$(document).ready(function(){

  $("ul.topnav").click(function() {
		
    $(this).addClass('show-filters').find(".filternav").show().show();

    $(this).parent().hover(function() {
    }, function(){
			$(this).find('ul.topnav').removeClass('show-filters');
      $(this).find(".filternav").fadeOut();
    });

  });
	
});	
</script>

[?php slot('a-subnav') ?]
<div id="a-subnav" class="blog">
  <div id="a-subnav-top" class="a-subnav-top"></div>
  <div class="a-subnav-wrapper">
    <ul class="a-admin-action-controls">
      <?php if ($this->configuration->hasFilterForm()): ?>
        <li class="filters">[?php echo jq_link_to_function("Filters", "$('#a-admin-filters-container').slideToggle()") ?]</li>
      <?php endif; ?>
        <li>[?php echo link_to('Edit Categories', '@a_blog_category_admin') ?]</li>
				<li>[?php echo link_to('Edit Posts', '@a_blog_admin') ?]</li>
        <li>[?php echo link_to('New  Posts', '@a_blog_admin_new') ?]</li>
        <li>[?php echo link_to('Edit Categories', '@a_blog_category_admin') ?]</li>
        <li>[?php echo link_to('Edit Comments', '@a_comment_admin') ?]</li>
        [?php //include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]   
    </ul>
  </div> 
  <div id="a-subnav-bottom" class="a-subnav-bottom"></div>
</div>
[?php end_slot() ?]

<div id="a-admin-container" class="[?php echo $sf_params->get('module') ?]">

  [?php include_partial('<?php echo $this->getModuleName() ?>/list_bar', array('filters' => $filters, 'configuration' => $configuration)) ?]
 
	<div id="a-admin-content" class="main">
		<?php if ($this->configuration->hasFilterForm()): ?>
		  [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'configuration' => $configuration)) ?]
		<?php endif; ?>

		[?php include_partial('<?php echo $this->getModuleName() ?>/flashes') ?]
		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
			<form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post" id="a-admin-batch-form">
		<?php endif; ?>
		[?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'form' => $filters)) ?]
				<ul class="a-admin-actions">
		      [?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
		    </ul>
		<?php if ($this->configuration->getValue('list.batch_actions')): ?>
		  </form>
		<?php endif; ?>
	</div>

  <div id="a-admin-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager)) ?]
  </div>
  

</div>
