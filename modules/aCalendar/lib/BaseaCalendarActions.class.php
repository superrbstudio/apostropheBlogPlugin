<?php

/**
 * Base actions for the apostropheBlogPlugin aCalendar module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aCalendar
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaCalendarActions extends apostropheBlogPluginEngineActions
{
   public function executeUpcoming(sfWebRequest $request)
   {
     $this->buildParams();

     $pager = new sfDoctrinePager('aBlogEvent', sfConfig::get('app_aCalendar_max_per_page', 10));
     $pager->setQuery(Doctrine::getTable('aBlogEvent')->addUpcomingEventsQuery());
     $pager->setPage($this->getRequestParameter('page', 1));
     $pager->init();

     $this->a_blog_events = $pager;
     
     $this->setTemplate('index');
   }

  public function executeIndex(sfWebRequest $request)
  {
    $this->nofollowIfNeeded();
    
    $q = Doctrine_Query::create()->from('aBlogEvent');
    $categories = aTools::getCurrentPage()->aBlogPageCategory->toArray();
    if(count($categories) > 0)
    {
      $categoryIds = array_map(create_function('$a', 'return $a["blog_category_id"];'),  $categories);
      
      if(in_array(null, $categoryIds)) 
      {
        if(count($categories) == 1) $uncat = true;
        $q->addWhere('aBlogEvent.category_id IS NULL OR aBlogEvent.category_id IN ?', array($categoryIds));
      }
      else
      {
        $q->addWhere('aBlogEvent.category_id IN ?', array($categoryIds));
      }
    }
     
    $pager = new sfDoctrinePager('aBlogEvent', sfConfig::get('app_aCalendar_max_per_page', 10));
    $q = Doctrine::getTable('aBlogEvent')->buildQuery($request, 'aBlogEvent', $q);
    if(!$request->hasParameter('year'))
    {
      Doctrine::getTable('aBlogEvent')->addUpcomingEventsQuery($q);
    }
    $pager->setQuery($q);
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->buildParams();

    $this->a_blog_events = $pager;
   }

   public function executeShow(sfWebRequest $request)
   {
     $this->a_blog_event = Doctrine::getTable('aBlogEvent')->findOneBySlug($this->getRequest()->getParameter('slug'));
     $this->forward404Unless($this->a_blog_event);

     $this->buildParams();
   }
}
