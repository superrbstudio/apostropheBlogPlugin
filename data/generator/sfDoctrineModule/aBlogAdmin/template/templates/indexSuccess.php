[?php use_helper('I18N', 'Date', 'jQuery') ?]
[?php include_partial('<?php echo $this->getModuleName() ?>/assets') ?]

<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	var sortLabel = $("a.a-sort-label").parent().parent();
	sortLabel.click(function(event) {
		event.preventDefault();		
    $(this).addClass('show-filters').find(".filternav").show();
    $(this).parent().hover(function() {
    }, function() {
			$(this).find('ul').removeClass('show-filters');
      $(this).find(".filternav").fadeOut();
    });
  });
});	
</script>

[?php slot('a-subnav') ?]
<div class="a-subnav-wrapper blog">
  <div class="a-subnav-inner">
    <ul class="a-admin-action-controls">
      	<li>[?php echo link_to('New  Post', '@a_blog_admin_new', array('class'=>'a-btn icon a-add')) ?]</li>
        <li>[?php echo link_to('Edit Categories', '@a_blog_category_admin') ?]</li>
				<li>[?php echo link_to('Edit Posts', '@a_blog_admin') ?]</li>
        <li>[?php echo link_to('Edit Comments', '@a_comment_admin') ?]</li>
        [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]   
    </ul>
  </div> 
</div>
[?php end_slot() ?]

<div class="a-admin-container [?php echo $sf_params->get('module') ?]">

  [?php include_partial('<?php echo $this->getModuleName() ?>/list_bar', array('filters' => $filters, 'configuration' => $configuration)) ?]
 
	<div class="a-admin-content main">


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

  <div class="a-admin-footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list_footer', array('pager' => $pager)) ?]
  </div>

</div>