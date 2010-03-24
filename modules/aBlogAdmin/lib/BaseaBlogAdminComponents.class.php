<?php

/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      P'UNK Avenue
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogAdminComponents extends sfComponents
{
	public function executeTagList()
  {
    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true, 'limit' => 10));
  }
}