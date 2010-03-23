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
    
    unset(
      $this['type'], $this['page_id'], $this['created_at'], $this['updated_at']
    );
    
    //TODO: Refactor query into model and change query to table_method, also need admins to get all categories
    $q = Doctrine_Query::create()
      ->from('aBlogCategory c');
    if(!sfContext::getInstance()->getUser()->hasCredential('admin'))
    {
      $q->innerJoin('c.Users u')
        ->where('u.id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId());
    }
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogCategory', 'query' => $q)));
    $this->setValidator('categories_list',
      new sfValidatorDoctrineChoice(array('model' => 'aBlogCategory', 'query' => $q, 'required' => false)));
      
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
