<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemForm extends BaseaBlogItemForm
{
  protected $engine = 'aBlog';

  public function setup()
  {
    parent::setup();
    $user = sfContext::getInstance()->getUser();
    
    unset(
      $this['type'], $this['page_id'], $this['created_at'], $this['updated_at'], $this['slug_saved']
    );
    
    $q = Doctrine::getTable($this->getModelName())->addCategoriesForUser($user->getGuardUser(), $user->hasCredential('admin'));
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => $this->getModelName(), 'query' => $q)));
    $this->setValidator('categories_list',
      new sfValidatorDoctrineChoice(array('multiple' => true, 'model' =>  $this->getModelName(), 'query' => $q, 'required' => false)));
         
    $q = Doctrine::getTable('sfGuardUser')->createQuery();
    if(!$user->hasCredential('admin'))
    {
      $q->addWhere('sfGuardUser.id = ?', $user->getGuardUser()->getId());
    }

		// This was crashing with errors if there was an empty / old / or incomplete aBlog entry in the project app.yml
		// By adding the if statement and the else, when you have a malformed aBlog entry in the project app.yml, it still returns the single column template
		// Which is bundled with the Plugin, so it's OK to assume we have it to return
    $templates = sfConfig::get('app_'.$this->engine.'_templates');
    $templateChoices = array();
	  foreach ($templates as $key => $template)
	  {
	    $templateChoices[$key] = $template['name'];
	  }

    $this->setWidget('template',
      new sfWidgetFormChoice(array('multiple' => false, 'choices' => $templateChoices)));
    $this->setValidator('template',
      new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_flip($templateChoices))));

    if(count($templateChoices) <= 1)
    {
      unset($this['template']);
    }

    if(!sfConfig::get('app_aBlog_comments', false))
    {
      unset($this['allow_comments']);
    }

    
    $this->setWidget('categories_list_add',
      new sfWidgetFormInputHidden());
    //TODO: Make this validator better, should check for duplicate categories, etc.
    $this->setValidator('categories_list_add',
      new sfValidatorPass());

    $this->setWidget('editors_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q)));
    $this->setValidator('editors_list',
      new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q, 'required' => false)));

    $this->setWidget('published_at', new sfWidgetFormJQueryDateTime(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')
		));

    $this->getWidgetSchema()->setDefault('published_at', date('Y/m/d H:i'));

    $this->widgetSchema['tags']       = new sfWidgetFormInput(array('default' => implode(', ', $this->getObject()->getTags())), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags']    = new sfValidatorString(array('required' => false));
  }
  
  public function updateCategoriesList($values)
  {
    $link = array();
    if(!is_array($values))
      $values = array();
    foreach ($values as $value)
    {
      $aBlogCategory = new aBlogCategory();
      $aBlogCategory['name'] = $value;
      $aBlogCategory->save();
      $link[] = $aBlogCategory['id'];
    }
    if(!is_array($this->values['categories_list']))
    {
      $this->values['categories_list'] = array();
    }
    $this->values['categories_list'] = array_merge($link, $this->values['categories_list']);
  }
  
  public function doSave($con = null)
  {
    $tags = $this->values['tags'];
    $tags = preg_replace('/\s\s+/', ' ', $tags);
    $tags = str_replace(', ', ',', $tags);

    $this->object->setTags($tags);
    $this->updateCategoriesList($this->values['categories_list_add']);
    parent::doSave($con);
  }
  
  public function saveCategoriesListAdd($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['categories_list_add']))
    {
      // somebody has unset this widget
      return;
    }
    
    if (null === $con)
    {
      $con = $this->getConnection();
    }
    
    $values = $this->getValue('categories_list_add');
    if (!is_array($values))
    {
      $values = array();
    }
    
    $link = array();
    foreach ($values as $value)
    {
      $aBlogCategory = new aBlogCategory();
      $aBlogCategory['name'] = $value;
      $aBlogCategory->save();
      $link[] = $aBlogCategory['id'];
    }
    
    if (count($link))
    {
      $this->object->link('Categories', array_values($link));
    }
  }
}
