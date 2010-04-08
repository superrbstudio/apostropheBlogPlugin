<?php use_helper('I18N') ?>
<?php $blog_post_url = url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>
		
<?php echo $form->renderHiddenFields() ?>

<div class="published section">

	<a href="#" class="a-btn big a-publish-post" onclick="return false;"><em class="publish">Publish</em><em class="unpublish">Unpublish</em></a>

	<div class="post-status">
		<?php echo $form['status']->renderRow() ?>
	</div>

	Publish now or <a href="#" onclick="return false;" class="post-date-toggle"><?php echo __('set a date', array(), 'messages') ?></a>
	<div class="post-date option">
	<?php echo $form['published_at']->render() ?>
	<?php echo $form['published_at']->renderError() ?>	
	</div>
	
</div>

<hr />

<div class="author section">

	<h4>Author</h4>

	<div class="post-author">
		<?php echo $a_blog_post->Author ?>
		<?php // We aren't letting them switch the user as per Rick's design ?>
		<?php // echo $form['author_id']->renderRow() ?>
	</div>

	<div class="post-editors">
		Allow others to edit this post
		<div class="post-editors-options option">
			<?php echo $form['editors_list']->renderRow()?>
		</div>
	</div>
	
</div>

<hr />

<div class="categories section" id="categories-section">
	<h4>Categories</h4>
	<?php echo $form['categories_list']->render() ?>
	<?php echo $form['categories_list']->renderError() ?>	
</div>

<hr />

<div class="tags section">
	<h4>Tags</h4>
	<?php echo $form['tags']->render() ?>
	<?php echo $form['tags']->renderError() ?>
  <script src='/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js'></script>
  <script type="text/javascript" charset="utf-8">
    $(function() {
      pkTagahead(<?php echo json_encode(url_for("taggableComplete/complete")) ?>);
    });
	</script>
  <?php include_component('aBlogAdmin','tagList', array('a_blog_post' => $form->getObject())) ?>
</div>

<hr />


<?php if(isset($form['template'])): ?>
<div class="template section">
  <h4>Template</h4>
  <?php echo $form['template']->render() ?>
  <?php echo $form['template']->renderError() ?>
</div>
<?php endif ?>

<?php if (0): ?>
	<?php // To Do: Comments are not built yet ?>	
	<div class="comments section">
		<?php  echo $form['allow_comments']->renderRow() ?>
	</div>
<?php endif ?>

<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
    aMultipleSelect('#categories-section', { 'choose-one': 'Add Categories',});
    aMultipleSelect('#editors-section', { 'choose-one': 'Add Editors', });
		checkAndSetPublish('<?php echo $blog_post_url ?>');
  });
</script>
