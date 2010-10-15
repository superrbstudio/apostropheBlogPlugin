<li class="a-admin-action-new"><?php echo link_to('<span class="icon"></span>'.__('New Post', array(), 'messages'), 'aBlogAdmin/new', array(  'class' => 'a-btn big icon a-add',)) ?></li>
<?php if (sfConfig::get('app_aBlog_disqus_enabled', true) && sfConfig::get('app_aBlog_disqus_shortname')): ?>
<li><?php echo link_to('Comments', 'http://'. sfConfig::get('app_aBlog_disqus_shortname') .'.disqus.com', array('class' => 'a-btn big', )) ?></li>
<?php endif ?>