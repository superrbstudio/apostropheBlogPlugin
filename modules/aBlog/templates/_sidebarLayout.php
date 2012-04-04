<?php $options = isset($options) ? $sf_data->getRaw('options') : null; ?>

<?php // Override me to reorder these and add custom stuff. For deeper changes you'll ?>
<?php // have to override _sidebar.php. Ideally you won't, because we update that partial ?>
<?php // to fix bugs and so forth. Here the only thing you have to watch out for is entirely ?>
<?php // new elements (which you could just go on living without) ?>

<?php include_slot('a_blog_sidebar_new_post') ?>
<?php include_slot('a_blog_sidebar_search') ?>
<?php include_slot('a_blog_sidebar_dates') ?>
<?php include_slot('a_blog_sidebar_categories') ?>
<?php include_slot('a_blog_sidebar_tags') ?>
<?php include_slot('a_blog_sidebar_authors') ?>
<?php include_slot('a_blog_sidebar_feeds') ?>
