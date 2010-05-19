<?php

/**
 * Base Components for the apostropheBlogPlugin aCalendar module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aCalendar
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaCalendarComponents extends sfComponents
{
  public function executeUpcomingEvents(sfWebRequest $request)
  {
    $limit = (isset($this->limit)) ? $this->limit : 5;
    
    $q = Doctrine::getTable('aBlogEvent')
      ->addUpcomingEventsQuery()
      ->limit($limit);
    
    $this->a_blog_events = $q->execute();
  }

  public function executeTagSidebar(sfWebRequest $request)
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = TagTable::findOrCreateByTagname($this->getRequestParameter('tag'));
    }

    $this->categories =  aTools::getCurrentPage()->BlogCategories;
    $aPageCategories = aTools::getCurrentPage()->aBlogPageCategory;
    
    $categoryIds = array();
    $null = false;
    foreach($aPageCategories as $category)
    {
      if(!is_null($category['blog_category_id']))
        $categoryIds[] = $category['blog_category_id'];
      else
        $null = true;
    }
    
    $this->popular = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aBlogEvent', true, 10, $null);
    $this->tags = Doctrine::getTable('aBlogCategory')->getTagsForCategories($categoryIds, 'aBlogEvent', $null);
    
    if(count($aPageCategories) == 0)
    {
      $this->categories = Doctrine::getTable('aBlogCategory')
        ->createQuery('c')
        ->orderBy('c.name')
        ->execute();
    }
  }
}
