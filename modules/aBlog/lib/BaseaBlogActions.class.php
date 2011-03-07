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
		$this->setPager();

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
		$this->setPager();
    
    $feed = sfFeedPeer::createFromObjects(
	    $this->a_blog_posts,
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

	protected function setPager()
	{
		$request = $this->getRequest();
	  $q = Doctrine_Query::create()->from('aBlogPost');
	  $this->nofollowIfNeeded();
  
	  $categories = aTools::getCurrentPage()->aBlogPageCategory->toArray();
	  if(count($categories) > 0)
	  {
	    $categoryIds = array_map(create_function('$a', 'return $a["blog_category_id"];'),  $categories);
    
	    if(in_array(null, $categoryIds)) 
	    {
	      if(count($categories) == 1) $uncat = true;
	      $q->addWhere('aBlogPost.category_id IS NULL OR aBlogPost.category_id IN ?', array($categoryIds));
	    }
	    else
	    {
	      $q->addWhere('aBlogPost.category_id IN ?', array($categoryIds));
	    }
	  }
  
	  $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
	  $pager->setQuery(Doctrine::getTable('aBlogPost')->buildQuery($request, 'aBlogPost', $q));
	  $pager->setPage($this->getRequestParameter('page', 1));
	  $pager->init();
  
	  $this->a_blog_posts = $pager;
	}
}
