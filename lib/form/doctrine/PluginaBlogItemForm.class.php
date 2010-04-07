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
  public function setup()
  {
    parent::setup();
    $user = sfContext::getInstance()->getUser();
    
    unset(
      $this['type'], $this['page_id'], $this['created_at'], $this['updated_at']
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
    
    if(false)
    {
      $this->setWidget('categories_list_add',
        new sfWidgetFormInputHidden());

      $this->setValidator('categories_list_add',
        new sfValidatorPass());
    }
    
    $this->setWidget('author_id',
      new sfWidgetFormDoctrineChoice(array('multiple' => false, 'model' => 'sfGuardUser', 'query' => $q)));
    $this->setValidator('author_id',
      new sfValidatorDoctrineChoice(array('model' => 'sfGuardUser', 'query' => $q, 'required' => true)));
      
    $this->setWidget('editors_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q)));
    $this->setValidator('editors_list',
      new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'query' => $q, 'required' => false)));
    
    $this->setWidget('template', 
      new sfWidgetFormChoice(array('multiple' => false, 'choices' => sfConfig::get('app_aBlog_templates'))));
    $this->setValidator('template',
      new sfValidatorChoice(array('required' => true, 'multiple' => false, 'choices' => array_flip(sfConfig::get('app_aBlog_templates')))));
      
    $this->widgetSchema['tags']       = new sfWidgetFormInput(array('default' => implode(', ', $this->getObject()->getTags())), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags']    = new sfValidatorString(array('required' => false));
  }
  
  public function updateSlugColumn($value)
  {
    if($this->getObject()->getSlug() == $value && $this->getObject()->getStatus() != 'published')
    {
      return $this->getValue('title'); 
    }
    return $value;
  }
  
  public function updateCategoriesList($values)
  {
    foreach ($values as $value)
    {
      $aBlogCategory = new aBlogCategory();
      $aBlogCategory['name'] = $value;
      $aBlogCategory->save();
      $link[] = $aBlogCategory['id'];
    }
    $this->values['categories_list'] = array_merge($link, $this->values['categories_list']);
  }
  
  public function doSave($con = null)
  {
    $tags = $this->values['tags'];
    $tags = preg_replace('/\s\s+/', ' ', $tags);
    $tags = str_replace(', ', ',', $tags);

    $this->object->setTags($tags);
    
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
