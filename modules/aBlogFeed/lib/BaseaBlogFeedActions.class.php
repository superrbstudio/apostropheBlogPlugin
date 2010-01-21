<?php

/**
 * Base actions for the apostropheBlogPlugin aBlogFeed module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogFeed
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogFeedActions extends sfActions
{
  public function executePosts(sfWebRequest $request)
  {
    $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
    $pager->setQuery(Doctrine::getTable('aBlogPost')->buildQuery($request));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->articles = $pager->getResults();
    
    $this->feed = sfFeedPeer::createFromObjects(
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
	  
	  $this->setTemplate('feed');
  }
}
