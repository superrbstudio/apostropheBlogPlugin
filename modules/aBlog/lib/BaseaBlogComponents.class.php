<?php

/**
 * Base Components for the aBlogPlugin aBlog module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogComponents extends sfComponents
{
  protected $modelClass = 'aBlogPost';
  
  
  public function executeSidebar()
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = TagTable::findOrCreateByTagname($this->getRequestParameter('tag'));
    }
    
    $this->popular = TagTable::getAllTagNameWithCount(null, array('model' => $this->modelClass, 'sort_by_popularity' => true, 'limit' => 10));

    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => $this->modelClass));

    if(is_null($this->categories))
    {
      $this->categories = Doctrine::getTable('aBlogCategory')
        ->createQuery('c')
        ->orderBy('c.name')
        ->execute();
    }

    if($this->reset == true)
    {
      $this->params['cat'] = array();
      $this->params['tag'] = array();
    }
  }
  
}
