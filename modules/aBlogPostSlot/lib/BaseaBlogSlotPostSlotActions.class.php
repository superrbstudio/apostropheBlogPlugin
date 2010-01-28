<?php

/**
 * Base actions for the apostropheBlogPlugin aBlogSlotPostSlot module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogSlotPostSlot
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogSlotPostSlotActions extends aBaseActions
{
  public function executeEdit(sfRequest $request)
  {
    $this->editSetup();

    $this->slot->value = $this->getRequestParameter('a_blog_post_id-' . $this->id);

    return $this->editSave();
  }
  
}
