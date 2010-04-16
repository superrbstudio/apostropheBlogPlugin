<?php use_helper('I18N') ?>
<?php $blog_post_url = url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>

<?php echo $form->renderHiddenFields() ?>

<?php include_partial('aBlogAdmin/sidebarPublishedSection', array('form' => $form, 'a_blog_post' => $a_blog_post, 'blog_post_url' => $blog_post_url)) ?>

<?php include_partial('aBlogAdmin/sidebarAuthorSection', array('form' => $form, 'a_blog_post' => $a_blog_post, 'blog_post_url' => $blog_post_url)) ?>

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

<?php if(isset($form['template'])): ?>
<hr />
<div class="template section">
  <h4>Template</h4>
  <?php echo $form['template']->render() ?>
  <?php echo $form['template']->renderError() ?>
</div>
<?php endif ?>

<?php if (1): ?>
<?php // To Do: Comments are not built yet ?>
	<hr />
  <div class="comments section">
	<h4>Comments: <span></span></h4> 
  
	<?php echo $form['allow_comments']->render() ?>
	<?php echo $form['allow_comments']->renderError() ?>
  </div>
<?php endif ?>

<script type="text/javascript" charset="utf-8">
  function updateBlogMulti()
  {
    updateBlogForm('<?php echo $blog_post_url ?>');
  }
  //TODO: Set slug value


  $(document).ready(function(){
	
    $('#a-admin-form').change(function(event) {
      updateBlog(event);
    });

    checkAndSetPublish('<?php echo $blog_post_url ?>');

    $('.a-sidebar-toggle').click(function(){
      $(this).toggleClass('open').next().toggle();
    })

    aMultipleSelect('#categories-section', { 'choose-one': 'Add Categories', 'add': 'New Category', 'onChange': 'updateBlogMulti'});
    aMultipleSelect('#editors-section', { 'choose-one': 'Add Editors', });

		// TODO: Check this using PHP, not on Dom Ready JS
		if ($('.section.comments input').attr('checked')) {
			$('.section.comments h4 span').text('Enabled');
		}
		else
		{	
			$('.section.comments h4 span').text('Disabled');		
		};

  });
</script>