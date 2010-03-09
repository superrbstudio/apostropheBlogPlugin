<?php

/**
 * Base actions for the aBlogPlugin aBlog module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogActions extends aEngineActions
{
  protected $modelClass = 'aBlogPost';
  
  public function preExecute()
  {
    parent::preExecute();
    $this->blogCategories = aTools::getCurrentPage()->BlogCategories->toArray();
    if(count($this->blogCategories) == 0)
    {
      $this->blogCategories = array();
    }
  }

  protected function buildQuery($request)
  {
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c');
    $categoryIds = array_map(create_function('$a', 'return $a["id"];'),  $this->blogCategories);
    $q->whereIn('c.id', $categoryIds);

    $routingOptions = $this->getRoute()->getOptions();    
    if(isset($routingOptions['filters']))
    {
      foreach($routingOptions['filters'] as $method)
      {
        Doctrine::getTable($this->modelClass)->$method($q, $request);
      }
    }
    
    return $q;
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->buildParams();
    $pager = new sfDoctrinePager($this->modelClass, 10);
    $pager->setQuery($this->buildQuery($request));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->pager = $pager;
    
    if($this->getRequestParameter('feed', false))
    {
      $this->getFeed();
      return sfView::NONE;
    }
    
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->aBlogPost = $this->getRoute()->getObject();
  }
  
  public function buildParams()
  {
    $this->params = array();

    // set our parameters for building pagination links
    $this->params['pagination']['year']  = $this->getRequestParameter('year');
    $this->params['pagination']['month'] = $this->getRequestParameter('month');
    $this->params['pagination']['day']   = $this->getRequestParameter('day');
    
    $date = strtotime($this->getRequestParameter('year', date('Y')).'-'.$this->getRequestParameter('month', date('m')).'-'.$this->getRequestParameter('day', date('d')));
    
    $this->dateRange = '';
    // set our parameters for building links that browse date ranges
    if ($this->getRequestParameter('day'))
    {
      $next = strtotime('tomorrow', $date);
      $this->params['next'] = array('year' => date('Y', $next), 'month' => date('m', $next), 'day' => date('d', $next));
      
      $prev = strtotime('yesterday', $date);
      $this->params['prev'] = array('year' => date('Y', $prev), 'month' => date('m', $prev), 'day' => date('d', $prev));
      
      $this->dateRange = 'day';
    }
    else if ($this->getRequestParameter('month'))
    {
      $next = strtotime('next month', $date);
      $this->params['next'] = array('year' => date('Y', $next), 'month' => date('m', $next));
      
      $prev = strtotime('last month', $date);
      $this->params['prev'] = array('year' => date('Y', $prev), 'month' => date('m', $prev));

      $this->dateRange = 'month';
    }
    else
    {
      $next = strtotime('next year', $date);
      $this->params['next'] = array('year' => date('Y', $next));
      
      $prev = strtotime('last year', $date);
      $this->params['prev'] = array('year' => date('Y', $prev));

      if ($this->getRequestParameter('year'))
      {
        $this->dateRange = 'year';
      }
    }
    
    // set our parameters for building links that set the date ranges
    $this->params['day'] = array('year' => date('Y', $date), 'month' => date('m', $date), 'day' => date('d', $date));
    $this->params['month'] = array('year' => date('Y', $date), 'month' => date('m', $date));
    $this->params['year'] = array('year' => date('Y', $date));
    $this->params['nodate'] = array();
    
    $this->addFilterParams('cat');
    $this->addFilterParams('tag');
    $this->addFilterParams('search');
  }
  
  public function addFilterParams($name)
  {
    // if there is a filter request, we need to add it to our date params
    if ($this->getRequestParameter($name))
    {
      foreach ($this->params as &$params)
      {
        $params[$name] = $this->getRequestParameter($name);
      }
    }
    
    // set an array for building a link to this filter (we don't want it to already have the filter in there)
    $this->params[$name] = $this->params['pagination'];
    unset($this->params[$name][$name]);
  }
  
  public function getFeed()
  {
    $this->articles = $this->pager->getResults();
    
    $this->feed = sfFeedPeer::createFromObjects(
      $this->articles,
      array(
        'format'      => 'rss',
        'title'       => sfConfig::get('app_aBlog_feed_title'),
        'link'        => '@a_blog',
        'authorEmail' => sfConfig::get('app_aBlog_feed_author_email'),
        'authorName'  => sfConfig::get('app_aBlog_feed_author_name'),
        'routeName'   => '@a_blog_post',
        'methods'     => array('description' => 'getFeedText')
      )
    );
    
    $this->getResponse()->setContent($this->feed->asXml());
  }
  
}
