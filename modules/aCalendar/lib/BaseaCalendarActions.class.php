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
    
    $q = Doctrine_Query::create()->from('aBlogEvent'.' a');
    $categories = aTools::getCurrentPage()->aBlogPageCategory->toArray();
    $q->leftJoin('a.Category r ON 1=1');
    $q->leftJoin('r.aBlogPageCategory apc on apc.page_id=' . aTools::getCurrentPage()->getId());
    if(count($categories) > 0)
    {
      $categoryIds = array_map(create_function('$a', 'return $a["blog_category_id"];'),  $categories);
      $q->whereIn('a.category_id', $categoryIds);
      if(in_array(null, $categoryIds)) 
      {
        if(count($categories) == 1) $uncat = true;
        $q->orWhere('a.category_id IS NULL');
      }   
    }  
     
    $pager = new sfDoctrinePager('aBlogEvent', sfConfig::get('app_aCalendar_max_per_page', 10));

    $pager->setQuery(Doctrine::getTable('aBlogEvent')->buildQuery($request, 'aBlogEvent', $q));
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
