<div class="a-no-items">
<?php $type = isset($type) ? $sf_data->getRaw('type') : null; ?>

<?php // Display some help information to admins so they know they can customize the Error404 page ?>
<?php if ($sf_user->hasCredential('admin')): ?>
	<div class="a-help a-no-items">
		<?php echo a_('You can customize the message displayed to users when there are no blog posts or events by adding content below.') ?>
	</div>
<?php endif ?>	

<?php a_area('body', array(
	'slug' => 'aErrors:'.$type, 
	'allowed_types' => array(
		'aRichText', 
		// 'aVideo',		
		// 'aSlideshow', 
		// 'aSmartSlideshow', 	
		// 'aFile',
		// 'aAudio',		
		// 'aFeed', 		
		// 'aButton', 
		// 'aBlog',
		// 'aEvent',
		// 'aEventSingle',
		// 'aText',
		// 'aRawHTML', 		
	),
  'type_options' => array(
		'aRichText' => array(
		  'tool' => 'Main',
			// 'allowed-tags' => array(),
			// 'allowed-attributes' => array('a' => array('href', 'name', 'target'),'img' => array('src')),
			// 'allowed-styles' => array('color','font-weight','font-style'), 
		), 	
		'aVideo' => array(
			'width' => 480, 
			'height' => false, 
			'resizeType' => 's',
			'flexHeight' => true, 
			'title' => false,
			'description' => false,			
		),		
		'aSlideshow' => array(
			'width' => 480, 
			'height' => false,
			'resizeType' => 's',  
			'flexHeight' => true, 
			'constraints' => array('minimum-width' => 480),
			'arrows' => true,
			'interval' => false,			
			'random' => false, 
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',       			
		),
		'aSmartSlideshow' => array(
			'width' => 480, 
			'height' => false,
			'resizeType' => 's',  
			'flexHeight' => true, 
			'constraints' => array('minimum-width' => 480),
			'arrows' => true,
			'interval' => false,			
			'random' => false, 
			'title' => false,
			'description' => false,
			'credit' => false,
			'position' => false,
			'itemTemplate' => 'slideshowItem',       			
		),
		'aFile' => array(
		), 
		'aAudio' => array(
			'width' => 480,
			'title' => true,
			'description' => true,
			'download' => true,
			'playerTemplate' => 'default',
		),
		'aFeed' => array(
			'posts' => 5,
			'links' => true,
			'dateFormat' => false,
			'itemTemplate' => 'aFeedItem',
			// 'markup' => '<strong><em><p><br><ul><li><a>',
			// 'attributes' => false,
			// 'styles' => false,
		),
		'aButton' => array(
			'width' => 480, 
			'flexHeight' => true, 
			'resizeType' => 's', 
			'constraints' => array('minimum-width' => 480),  
			'rollover' => true, 
			'title' => true, 
			'description' => false
		),		
		'aBlog' => array(
			'slideshowOptions' => array(
				'width' => 480, 
				'height' => 320
			),
		),
		'aEvent' => array(
			'slideshowOptions' => array(
				'width' => 340, 
				'height' => 220
			),
		),
		'aEventSingle' => array(
			'slideshowOptions' => array(
				'width' => 340, 
				'height' => 220
			),
		),
    'aText' => array(
			'multiline' => true
		),
		'aRawHTML' => array(
		), 
	))) ?>
</div>	