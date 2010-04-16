<?php use_helper('I18N') ?>

<?php echo $form->renderHiddenFields() ?>

<div class="published section">
	<div class="status subsection">
		<?php include_partial('aBlogAdmin/form_status', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
	<div class="published_at subsection">
		<?php include_partial('aBlogAdmin/form_published_at', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
</div>

<hr />
<div class="author section">
	<div class="author_id subsection">
		<?php include_partial('aBlogAdmin/form_author_id', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
	<div class="editors_list subsection">
		<?php include_partial('aBlogAdmin/form_editors_list', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
</div>

<hr />
<div class="categories section" id="categories-section">
	<div class="categories_list subsection">
		<?php include_partial('aBlogAdmin/form_categories_list', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
</div>

<hr />
<div class="tags section">
	<div class="tags subsection">
		<?php include_partial('aBlogAdmin/form_tags', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>
	</div>
</div>

<?php if(isset($form['template'])): ?>
<hr />
<div class="template section">
	<div class="template subsection">
		<?php include_partial('aBlogAdmin/form_template', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>		
	</div>
</div>
<?php endif ?>

<hr />
<div class="comments section">
	<div class="allow_comments subsection">
		<?php include_partial('aBlogAdmin/form_allow_comments', array('form' => $form, 'a_blog_post' => $a_blog_post)) ?>		
	</div>
</div>

<script type="text/javascript" charset="utf-8">
  function updateBlogMulti()
  {
    updateBlogForm('<?php echo url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>');
  }

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


		if ($('.section.comments input').attr('checked')) {
			$('.section.comments h4 span').text('Enabled');
		}
		else
		{	
			$('.section.comments h4 span').text('Disabled');		
		};

  });
</script>