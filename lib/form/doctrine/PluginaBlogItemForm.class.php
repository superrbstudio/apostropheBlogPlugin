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
      $this['type'], $this['body_id'], $this['created_at'], $this['updated_at']
    );
    
    //TODO: Refactor query into model and change query to table_method, also need admins to get all categories
    
    $q = Doctrine_Query::create()
      ->from('aBlogCategory c')
      ->innerJoin('c.Users u')
      ->where('u.id = ?', sfContext::getInstance()->getUser()->getGuardUser()->getId());
    
    $this->setWidget('categories_list',
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogCategory', 'query' => $q)));
    
  }
  
}
