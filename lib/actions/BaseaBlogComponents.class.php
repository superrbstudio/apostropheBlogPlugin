<?php

/**
 * Base Components for the aBlogPlugin aBlog module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      P'unk Avenue
 * @version     SVN: $Id: BaseComponents.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogComponents extends sfComponents
{
  protected $modelClass = 'aBlogPost';

  public function preExecute()
  {
    parent::preExecute();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  public function executeSidebar()
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = Doctrine::getTable('Tag')->findOrCreateByTagname($this->getRequestParameter('tag'));
    }

		$uncategorized = false;
		
    if(is_null($this->categories) || !count($this->categories))
    {
			$uncategorized = true;
      $this->categories = Doctrine::getTable('aBlogCategory')
        ->createQuery('c')
        ->addWhere('c.posts = ?', true)
        ->orderBy('c.name')
        ->execute();
    }
    
    $categoryIds = array();
    foreach($this->categories as $category)
    {
      $categoryIds[] = $category['id'];
    }
		
		// Tags that are applied to uncategorized blog posts are allowed if there
		// is no category for this engine page
		if ($uncategorized)
		{
			$this->popular = TagTable::getAllTagNameWithCount(null, array('limit' => 10, 'model' => 'aBlogPost', 'sort_by_popularity' => true));
			$this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => false));
		}
		else
		{
	    $this->popular = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aBlogPost', true, 10);
	    $this->tags = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aBlogPost');
		}
		
    if($this->reset == true)
    {
      $this->params['cat'] = array();
      $this->params['tag'] = array();
    }
  }
  
}
