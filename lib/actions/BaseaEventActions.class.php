<?php
/**
 * Base actions for the aEventPlugin aEvent module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEvent
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaEventActions extends BaseaBlogActions
{
  protected $modelClass = 'aEvent';

  public function preExecute()
  {
    parent::preExecute();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addStylesheet('/apostropheBlogPlugin/css/aBlog.css');
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

	public function executeIndex(sfWebRequest $request)
	{
		parent::executeIndex($request);	
		if (sfConfig::get('app_aEvents_display_calendar'))
		{
			$this->calendar = $this->buildCalendar($request);			
		}
		else
		{
			$this->calendar = false;
		}
	}
  
  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aEvent = $this->getRoute()->getObject();
    $this->blogCategories = $this->blogCategories = aBlogCategoryTable::getCategoriesForPage($this->page);
    $this->forward404Unless($this->aEvent);
    $this->forward404Unless($this->aEvent['status'] == 'published');
    aBlogItemTable::populatePages(array($this->aEvent));
		if (sfConfig::get('app_aEvents_display_calendar'))
		{
			$this->calendar = $this->buildCalendar($request);			
		}
		else
		{
			$this->calendar = false;
		}
  }

  protected function buildQuery($request)
  {
    $q = parent::buildQuery($request);
    if(!$request->hasParameter('year', false))
      Doctrine::getTable('aEvent')->addUpcoming($q);
    return $q;
  }
  
  public function getFeed()
  {
    $this->articles = $this->pager->getResults();
    
    $title = sfConfig::get('app_aEvent_feed_title', $this->page->getTitle());
    $this->feed = sfFeedPeer::createFromObjects(
      $this->articles,
      array(
        'format'      => 'rss',
        'title'       => $title,
        'link'        => '@a_event',
        'authorEmail' => sfConfig::get('app_aEvent_feed_author_email'),
        'authorName'  => sfConfig::get('app_aEvent_feed_author_name'),
        'routeName'   => '@a_event',
        'methods'     => array('description' => 'getFeedText')
      )
    );
    
    $this->getResponse()->setContent($this->feed->asXml());
  }

	public function buildCalendar($request)
	{	
		$date = $request->getParameter('year', date('Y')).'-'.$request->getParameter('month', date('m')).'-'.$request->getParameter('month', date('d'));
		
		$monthRequest = clone $request;
		$monthRequest->getParameterHolder()->remove('day');
		$query = $this->buildQuery($monthRequest);
		$aEvents = $query->execute();
			
		$calendar['events'] = new sfEventCalendar('month', $date);
		
		foreach ($aEvents as $aEvent)
		{
			$calendar['events']->addEvent($aEvent->getStartDate(), array('event' => $aEvent));
		}
		
		$calendar['month'] = date('F', strtotime($date));
		$calendar['year'] = date('Y', strtotime($date));
			
		$next = strtotime('next month', strtotime($date));
    $calendar['params']['next'] = array('year' => date('Y', $next), 'month' => date('m', $next));
    
    $prev = strtotime('last month', strtotime($date));
    $calendar['params']['prev'] = array('year' => date('Y', $prev), 'month' => date('m', $prev));

		return $calendar;	
	}
}
