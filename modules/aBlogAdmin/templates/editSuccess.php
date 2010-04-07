<?php slot('body_class') ?>a-blog <?php echo $sf_params->get('module'); ?> <?php echo $sf_params->get('action') ?><?php end_slot() ?>

<?php use_helper('I18N', 'Date', 'jQuery', 'a') ?>
<?php include_partial('aBlogAdmin/assets') ?>

<div class="a-admin-container <?php echo $sf_params->get('module') ?>">
	
  <?php // include_partial('aBlogAdmin/form_bar', array('title' => __('Edit Blog Post', array(), 'messages'))) ?>

	<?php slot('a-subnav') ?>
  <div class="a-subnav-wrapper blog">
  	<div class="a-subnav-inner">	
       <ul class="a-admin-action-controls">
				<li><a href="">All Posts</a></li>
         <?php include_partial('aBlogAdmin/list_actions', array('helper' => $helper)) ?>
       </ul>
     </div> 
  </div>
	<?php end_slot() ?>
  
  <?php include_partial('aBlogAdmin/flashes') ?>
	
	<div class="a-admin-content main">	
		<input type="text" id="a_blog_post_title_interface" value="<?php echo $a_blog_post->title ?>" />
		<div id="a_blog_post_permalink_interface">http://site/blog/url/<?php echo $a_blog_post->slug ?></div>
  	<?php include_partial('aBlog/'.$a_blog_post->getTemplate(), array('a_blog_post' => $a_blog_post)) ?>
  </div>

  <div class="a-admin-sidebar">
    <div id='a-admin-blog-post-form'>
    <?php include_partial('aBlogAdmin/form', array('a_blog_post' => $a_blog_post, 'form' => $form)) ?>
    </div>
  </div>
  
  <div class="a-admin-footer">
    <?php include_partial('aBlogAdmin/form_footer', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
  </form>
<?php //include_partial('aBlogAdmin/form_actions', array('a_blog_post' => $a_blog_post, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
</div>


<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){

    $('#a-admin-form').change(function() {
			updateBlogForm();
    });

		$('#a_blog_post_title_interface').change(function(){
			$('#a_blog_post_title').val($(this).val());
			updateBlogForm();
		}).focus();
				
		function updateBlogForm()
		{
      jQuery.ajax({
          type:'POST',
          dataType:'html',
          data:jQuery('#a-admin-form').serialize(),
          success:function(data, textStatus){
          jQuery('#a-admin-blog-post-form').html(data);
        },
        url: '<?php echo url_for('@a_blog_admin_update?slug='.$a_blog_post['slug']) ?>'
      });
		}

  });
</script>