<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['count'])): ?>
	<?php foreach ($aBlogPosts as $aBlogPost): ?>
    <?php // TODO: passing a variable as both underscore and intercap is silly clean this up make the partials consistent but look out for overrides ?>
		<?php include_partial('aBlog/'.$options['template'].$options['suffix'], array('aBlogPost' => $aBlogPost, 'a_blog_post' => $aBlogPost, 'edit' => false, 'options' => $options)) ?>
	<?php endforeach ?>
<?php endif ?>
