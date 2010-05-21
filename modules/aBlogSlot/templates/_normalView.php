<?php include_partial('a/simpleEditButton', array('name' => $name, 'pageid' => $pageid, 'permid' => $permid)) ?>
<?php foreach ($aBlogPosts as $aBlogPost): ?>
	<?php include_partial('aBlogSingleSlot/post', array('options' => $options, 'aBlogItem' => $aBlogPost)) ?>
<?php endforeach ?>
