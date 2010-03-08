<?php

/**
 * Base actions for the apostropheBlogPlugin aBlog module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlog
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogActions extends apostropheBlogPluginEngineActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()->from('aBlogPost'.' a');
    $categories = aTools::getCurrentPage()->BlogCategories->toArray();
    if(count($categories) > 0)
    {
      $categoryIds = array_map(create_function('$a', 'return $a["id"];'),  $categories);
      $q->whereIn('a.Category.id', $categoryIds);
    }
    
    $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
    $pager->setQuery(Doctrine::getTable('aBlogPost')->buildQuery($request, 'aBlogPost', $q));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->a_blog_posts = $pager;

    $this->buildParams();

    // We want to include a link to the feed with the filters the user has enabled...
    // but it shouldn't be filtering by any of the date information, just tags/categories
    $feedParams = $this->params['pagination'];
    unset($feedParams['year']);
    unset($feedParams['month']);
    unset($feedParams['day']);
        
    aFeed::addFeed($request, aUrl::addParams('aBlogFeed/posts', $feedParams));
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneBySlug($this->getRequest()->getParameter('slug'));
    $this->forward404Unless($this->a_blog_post);

    $this->buildParams();
  }
  
  public function executeFeed(sfWebRequest $request)
  {
    $q = Doctrine_Query::create()->from('aBlogPost'.' a');
    $categories = aTools::getCurrentPage()->BlogCategories->toArray();
    if(count($categories) > 0)
    {
      $categoryIds = array_map(create_function('$a', 'return $a["id"];'),  $categories);
      $q->whereIn('a.Category.id', $categoryIds);
    }
    
    $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
    $pager->setQuery(Doctrine::getTable('aBlogPost')->buildQuery($request, 'aBlogPost', $q));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->articles = $pager->getResults();
    
    $feed = sfFeedPeer::createFromObjects(
	    $this->articles,
	    array(
	      'format'      => 'rss',
	      'title'       => sfConfig::get('app_aBlog_feed_title'),
	      'link'        => '@a_blog',
	      'authorEmail' => sfConfig::get('app_aBlog_feed_author_email'),
	      'authorName'  => sfConfig::get('app_aBlog_feed_author_name'),
	      'routeName'   => '@a_blog_post',
	      'methods'     => array('description' => 'getBody')
	    )
	  );
	  $this->getResponse()->setContent($feed->asXml());
	  return sfView::NONE;
  }
}
