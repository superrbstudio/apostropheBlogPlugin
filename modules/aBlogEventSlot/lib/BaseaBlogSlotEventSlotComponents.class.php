<?php

/**
 * Base actions for the apostropheBlogPlugin aBlogSlotEventSlot module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogSlotEventSlot
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogSlotEventSlotComponents extends aBaseComponents
{
  public function executeEditView()
  {
    $this->setup();
    
    $q = Doctrine::getTable('aBlogEvent')
      ->createQuery('e')
      ->orderBy('e.title');
    
    $this->a_blog_events = array();
    foreach ($q->execute() as $event)
    {
      $this->a_blog_events[$event->getId()] = $event->getTitle();
    }
  }

  public function executeNormalView()
  {
    $this->setup();

    $this->a_blog_event = Doctrine::getTable('aBlogEvent')->find($this->slot->value);
  }
}
