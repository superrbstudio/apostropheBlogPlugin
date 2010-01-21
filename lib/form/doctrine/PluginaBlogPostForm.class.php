<?php

/**
 * PluginaBlogPost form.
 *
 * @package    form
 * @subpackage aBlogPost
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginaBlogPostForm extends BaseaBlogPostForm
{
  public function setup()
  {
    parent::setup();

    $user = sfContext::getInstance()->getUser();
    
    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['slug'],
      $this['type'],
      $this['version'],
      $this['media']
    );

    if (!$user->hasPermission('blog_admin'))
    {
      $this->widgetSchema['author_id']  = new sfWidgetFormInputHidden();
    }
    $this->widgetSchema->setLabel('author_id', 'Author');
    $this->setDefault('author_id', $user->getGuardUser()->getId());

    $this->widgetSchema['excerpt']    = new sfWidgetFormRichTextarea(array('editor' => 'fck', 'height' => '200', 'width' => '600',  ));
		$this->validatorSchema['excerpt'] = new sfValidatorHtml(array('required' => false));

	  $this->widgetSchema['body']       = new sfWidgetFormRichTextarea(array('editor' => 'fck', 'height' => '400', 'width' => '600',  ));
		$this->validatorSchema['body']    = new sfValidatorHtml(array('required' => false));
    
    $this->widgetSchema['category_id']->setLabel('Category');
    
    $this->widgetSchema['tags']       = new sfWidgetFormInput(array('default' => implode(', ', $this->getObject()->getTags())), array('class' => 'tag-input', 'autocomplete' => 'off'));
		$this->validatorSchema['tags']    = new sfValidatorString(array('required' => false));
  }

	public function doSave($con = null)
	{
	  $tags = $this->values['tags'];
    $tags = preg_replace('/\s\s+/', ' ', $tags);
    $tags = str_replace(', ', ',', $tags);

		$this->object->setTags($tags);
    
		parent::doSave($con);
	}
}