<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php if (isset($values['count'])): ?>
	<?php foreach ($aBlogPosts as $aBlogPost): ?>
		<?php include_partial('aBlog/'.$aBlogPost['template'].'_slot', array('aBlogPost' => $aBlogPost, 'options' => $options)) ?>
	<?php endforeach ?>
<?php endif ?>
