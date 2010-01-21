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
    $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
    $pager->setQuery(Doctrine::getTable('aBlogPost')->buildQuery($request));
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
}
