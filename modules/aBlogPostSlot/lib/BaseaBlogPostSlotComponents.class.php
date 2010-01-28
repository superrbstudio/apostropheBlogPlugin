<?php

/**
 * Base actions for the apostropheBlogPlugin aBlogSlotPostSlot module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogSlotPostSlot
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogSlotPostSlotComponents extends aBaseComponents
{
  public function executeEditView()
  {
    $this->setup();
    
    $q = Doctrine::getTable('aBlogPost')
      ->createQuery('p')
      ->orderBy('p.title');
    
    $this->a_blog_posts = array();
    foreach ($q->execute() as $post)
    {
      $this->a_blog_posts[$post->getId()] = $post->getTitle();
    }
  }

  public function executeNormalView()
  {
    $this->setup();

    $this->a_blog_post = Doctrine::getTable('aBlogPost')->find($this->slot->value);
  }
}
