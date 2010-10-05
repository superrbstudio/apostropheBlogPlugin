<li class="a-admin-action-new"><?php echo link_to(__('New Post', array(), 'messages'), 'aBlogAdmin/new', array(  'class' => 'a-btn big icon a-add',)) ?></li>
<?php  if (sfConfig::get('app_aBlog_disqus_enabled', true)):?>
<li><?php echo link_to('Comments', "http://<?php echo(sfConfig::get('app_aBlog_disqus_shortname'))?>.disqus.com", array('class' => 'a-btn big', )) ?></li>
<?php endif ?>