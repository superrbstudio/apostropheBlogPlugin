<?php

/**
 * PluginaBlogEvent form.
 *
 * @package    form
 * @subpackage aBlogEvent
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginaBlogEventForm extends BaseaBlogEventForm
{
  public function setup()
  {
    parent::setup();

    unset(
      $this['created_at'],
      $this['updated_at'],
      $this['slug'],
      $this['type'],
      $this['version'],
      $this['published_at'],
      $this['media']
    );

    if (!$this->getObject()->isNew())
    {
      unset($this['published']);
    }

    $this->widgetSchema['author_id'] = new sfWidgetFormInputHidden();
    $this->setDefault('author_id', sfContext::getInstance()->getUser()->getGuardUser()->getId());

    $this->widgetSchema['excerpt'] = new sfWidgetFormRichTextarea(array('editor' => 'fck', 'height' => '200', 'width' => '360',  ));
	  $this->widgetSchema['body']  = new sfWidgetFormRichTextarea(array('editor' => 'fck', 'height' => '400', 'width' => '360',  ));
    
    $this->widgetSchema['category_id']->setLabel('Category');
    
    $this->validatorSchema['start_date'] = new sfValidatorDateTime(array(
      'required' => true,
    ));

    $this->validatorSchema['end_date'] = new sfValidatorDateTime(array(
      'required' => true,
    ));
    
    $this->widgetSchema['start_date'] = new sfWidgetFormJQueryDate(array(
      'image' => '/apostrophePlugin/images/a-icon-datepicker.png', 
      'config' => '{}',
    ));

    $this->widgetSchema['start_time'] = new sfWidgetFormJQueryTime(array(
      'image' => '/apostrophePlugin/images/a-icon-timepicker.png', 
      'config' => '{}',
    ));
  
    $this->widgetSchema['end_date'] = new sfWidgetFormJQueryDate(array(
      'image' => '/apostrophePlugin/images/a-icon-datepicker.png',
      'config' => '{}',
    )); 

    $this->widgetSchema['end_time'] = new sfWidgetFormJQueryTime(array(
      'image' => '/apostrophePlugin/images/a-icon-timepicker.png', 
      'config' => '{}',
    ));
    
    $this->widgetSchema['tags'] = new sfWidgetFormInput(array('default' => implode(', ', $this->getObject()->getTags())), array('class' => 'tag-input', 'autocomplete' => 'off'));
		$this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));
		$this->validatorSchema['body'] = new sfValidatorHtml(array('required' => false));
		$this->validatorSchema['excerpt'] = new sfValidatorHtml(array('required' => false));
    sfContext::getInstance()->getConfiguration()->loadHelpers('jQuery');
    $r = sfContext::getInstance()->getResponse();
    $r->addStylesheet(sfConfig::get('sf_jquery_web_dir').'/css/JqueryAutocomplete');
    jq_add_plugins_by_name(array('ui'));
    jq_add_plugins_by_name(array("autocomplete"));
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