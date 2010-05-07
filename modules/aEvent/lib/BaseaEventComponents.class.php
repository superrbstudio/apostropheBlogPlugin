<?php

/**
 * Base Components for the apostropheBlogPlugin aEvent module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEvent
 * @author      Dan Ordille
 */
abstract class BaseaEventComponents extends sfComponents
{
  protected $modelClass = 'aEvent';
  
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
        ->where('c.events = ?', true)
        ->execute();
    }

    if($this->reset == true)
    {
      $this->params['cat'] = array();
      $this->params['tag'] = array();
    }
  }

}
