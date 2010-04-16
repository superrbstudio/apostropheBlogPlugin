<?php use_helper('I18N') ?>

<?php echo $form->renderHiddenFields() ?>

<div class="published section">
	<?php include_partial('aBlogAdmin/form_status', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	<?php include_partial('aBlogAdmin/form_published_at', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
</div>

<hr />
<div class="author section">
	<?php include_partial('aBlogAdmin/form_author_id', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	<?php include_partial('aBlogAdmin/form_editors_list', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
</div>

<hr />
<div class="categories section" id="categories-section">
	<?php include_partial('aBlogAdmin/form_categories_list', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
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
    updateBlogForm('<?php echo url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>');
  }
  //TODO: Set slug value


  $(document).ready(function(){
	
    $('#a-admin-form').change(function(event) {
      updateBlog(event);
    });

    checkAndSetPublish('<?php echo url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>');

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