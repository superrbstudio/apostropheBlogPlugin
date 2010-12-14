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
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }
  
	public function executeIndex(sfWebRequest $request)
	{
		parent::executeIndex($request);
    if($this->getRequestParameter('feed', false))
    {
      // Don't have to call getFeed again, the parent implementation did that
      return sfView::NONE;
    }
		if (sfConfig::get('app_aEvents_display_calendar'))
		{
			$this->calendar = $this->buildCalendar($request);			
		}
	}

  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aEvent = $this->getRoute()->getObject();
		$this->categories = aCategoryTable::getCategoriesForPage($this->page);
		$this->forward404Unless($this->aEvent);
    $this->forward404Unless($this->aEvent['status'] == 'published' || $this->getUser()->isAuthenticated());
		$this->preview = $this->getRequestParameter('preview');
    aBlogItemTable::populatePages(array($this->aEvent));
		if (sfConfig::get('app_aEvents_display_calendar'))
		{
			$this->calendar = $this->buildCalendar($request);			
		}
  }

  public function executeIcal(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aEvent = $this->getRoute()->getObject();
		$this->categories = aCategoryTable::getCategoriesForPage($this->page);
		$this->forward404Unless($this->aEvent);
    $this->forward404Unless($this->aEvent['status'] == 'published' || $this->getUser()->isAuthenticated());
    aBlogItemTable::populatePages(array($this->aEvent));
		
		header("Content-type: text/calendar");
    header('Content-disposition: attachment; filename=' . str_replace('.', '-', $this->getRequest()->getHost() . '-' . $this->aEvent->id) . '.vcs');
    $published_at = $this->aEvent->getVcalPublishedAtDateTime();
    $start = $this->aEvent->getVcalStartDateTime();
    $end = $this->aEvent->getVcalEndDateTime();
    $title = aString::toVcal(aHtml::toPlaintext($this->aEvent->getTitle()));
    $body = aString::toVcal(aHtml::toPlaintext($this->aEvent->Page->getAreaText('blog-body')));
    $location = aString::toVcal($this->aEvent->getLocation());
    // This has to be valid hex or Outlook will wig out. (Valid decimal also happens to be valid hex)
    $uid = $this->aEvent->id;
    // ACHTUNG: this was formatted with considerable care to be compatible with
    // Outlook 2003 for Windows. If you make pretty much any change here, make
    // very sure it still works with Outlook 2003 for Windows, which is
    // quite picky and requires some "optional" things too
    echo(<<<EOM
BEGIN:VCALENDAR
PRODID:-//punkave//apostrophe 1.x//EN
VERSION:1.0
TZ:0
BEGIN:VEVENT
CATEGORIES:MEETING
DTSTART:$start
DTEND:$end
DTSTAMP:$published_at
SUMMARY:$title
DESCRIPTION:$body
LOCATION:$location
UID:$uid
END:VEVENT
END:VCALENDAR
EOM
    );
    exit(0);
  }

  public function executeIcalFeed(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aEvent = $this->getRoute()->getObject();
		$this->categories = aCategoryTable::getCategoriesForPage($this->page);
		$this->forward404Unless($this->aEvent);
    $this->forward404Unless($this->aEvent['status'] == 'published' || $this->getUser()->isAuthenticated());
    aBlogItemTable::populatePages(array($this->aEvent));

		header("Content-type: text/calendar");
    header('Content-disposition: attachment; filename=' . str_replace('.', '-', $this->getRequest()->getHost() . '-' . $this->aEvent->id) . '.ics');
    $start = $this->aEvent->getVcalStartDateTime();
    $end = $this->aEvent->getVcalEndDateTime();
    $title = aString::toVcal(aHtml::toPlaintext($this->aEvent->getTitle()));
    $body = aString::toVcal(aHtml::toPlaintext($this->aEvent->Page->getAreaText('blog-body')));
    echo(<<<EOM
BEGIN:VCALENDAR
VERSION:2.0
BEGIN:VEVENT
CATEGORIES:MEETING
DTSTART:$start
DTEND:$end
SUMMARY:$title
DESCRIPTION:$body
CLASS:PRIVATE
END:VEVENT
END:VCALENDAR
EOM
    );
    exit(0);
  }
    
  protected function buildQuery($request)
  {
    $q = $this->filterByPageCategory();

    if($request->hasParameter('year'))
      Doctrine::getTable($this->modelClass)->filterByYMD($request->getParameter('year'), $request->getParameter('month'), $request->getParameter('day'), $q);
    else
      Doctrine::getTable('aEvent')->addUpcoming($q);
    if($request->hasParameter('cat'))
      Doctrine::getTable($this->modelClass)->filterByCategory($request->getParameter('cat'), $q);
    if($request->hasParameter('tag'))
      Doctrine::getTable($this->modelClass)->filterByTag($request->getParameter('tag'), $q);
    Doctrine::getTable($this->modelClass)->addPublished($q);
    $q->orderBy('published_at desc');

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

  public function getIcalFeed()
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
