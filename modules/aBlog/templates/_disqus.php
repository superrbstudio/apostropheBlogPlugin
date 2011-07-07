<?php $id = $sf_data->getRaw('id') ?>
<?php if (isset($post)): ?>
  <?php $post = $sf_data->getRaw('post') ?>
<?php endif ?>
<?php if (sfConfig::get('app_aBlog_disqus_enabled', true) && sfConfig::get('app_aBlog_disqus_shortname')): ?>
	<?php $disqus_shortname = sfConfig::get('app_aBlog_disqus_shortname') ?>
	<div id="disqus_thread"></div>
	<script type="text/javascript">
	  <?php // Newer versions pass the post object so we can access a custom disqus identifier ?>
	  <?php // from a Wordpress import etc. Older versions do not and we shouldn't break older ?>
	  <?php // overrides of those templates. The prefix app.yml option only makes sense when we ?>
	  <?php // are not using a legacy identifier imported from another system ?>
    var disqus_identifier = <?php echo json_encode(aBlogItemTable::getDisqusIdentifier(isset($post) ? $post : null, $id)) ?>;
	  (function() {
	   var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	   dsq.src = "http://<?php echo $disqus_shortname ?>.disqus.com/embed.js";
	   (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	  })();
	<?php if (sfConfig::get('app_aBlog_disqus_developer', true)): ?>
		var disqus_developer = true;
	<?php endif ?>
	</script>
	<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript=<?php echo $disqus_shortname ?>">comments powered by Disqus.</a></noscript>
	<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>
<?php endif ?>