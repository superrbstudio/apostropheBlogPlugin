<?php
require_once dirname(__FILE__).'/../../aBlog/lib/BaseaBlogActions.class.php';
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
  
  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aEvent = $this->getRoute()->getObject();
  }
  
  public function getFeed()
  {
    $this->articles = $this->pager->getResults();
    
    $this->feed = sfFeedPeer::createFromObjects(
      $this->articles,
      array(
        'format'      => 'rss',
        'title'       => sfConfig::get('app_aEvent_feed_title'),
        'link'        => '@a_event',
        'authorEmail' => sfConfig::get('app_aEvent_feed_author_email'),
        'authorName'  => sfConfig::get('app_aEvent_feed_author_name'),
        'routeName'   => '@a_event',
        'methods'     => array('description' => 'getFeedText')
      )
    );
    
    $this->getResponse()->setContent($this->feed->asXml());
  }
}
