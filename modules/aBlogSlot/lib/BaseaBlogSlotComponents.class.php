<?php

/**
 * Base actions for the apostropheBlogPlugin aBlogSlot module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlogSlot
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogSlotComponents extends BaseaSlotComponents
{
  public function executeEditView()
  {
    $this->setup();
    
    // In here the user could maybe select categories or tag to configure
    // the blog slot on this specific page.
  }

  public function executeNormalView()
  {
    $this->setup();
    
    $pager = new sfDoctrinePager('aBlogPost', sfConfig::get('app_aBlog_max_per_page', 10));
    $pager->setQuery($this->buildQuery());
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->a_blog_posts = $pager;

    $this->buildParams();

    $feedParams = $this->params['pagination'];
    unset($feedParams['year']);
    unset($feedParams['month']);
    unset($feedParams['day']);
    
    if ($this->getRequest()->hasParameter('p'))
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneBySlug($this->getRequest()->getParameter('p'));
    }
    else
    {
      $this->a_blog_post = false;
    }
    
    // aFeed::addFeed($request, aUrl::addParams('aBlog/feed', $feedParams));
  }
  
  public function buildQuery()
  {
    $this->params = array();
    
    if ($this->getRequestParameter('tag'))
    {
      $q = PluginTagTable::getObjectTaggedWithQuery('aBlogPost', $this->getRequestParameter('tag'), null, array('nb_common_tag' => 1));
    }
    else
    {
      $q = Doctrine_Query::create()->from('aBlogPost a');
    }
    
    if ($this->getRequestParameter('search'))
    {
      $q = Doctrine::getTable('aBlogPost')->addSearchQuery($q, $this->getRequestParameter('search'));
    }
    
    $rootAlias = $q->getRootAlias();
    
    // if it's an RSS feed, we don't want to be concerned with a time frame, just give us the latest stuff
    if ($this->getRequestParameter('format') != 'rss')
    {
      $q->addWhere($rootAlias.'.published_at > ?', $this->getRequestParameter('year', date('Y')).'-'.$this->getRequestParameter('month', 1).'-'.$this->getRequestParameter('day', 1).' 0:00:00')
        ->addWhere($rootAlias.'.published_at < ?', $this->getRequestParameter('year', date('Y')).'-'.$this->getRequestParameter('month', 12).'-'.$this->getRequestParameter('day', 31).' 23:59:59');
    }
    
    if ($this->getRequestParameter('cat'))
    {
      $q->innerJoin($rootAlias.'.Category c WITH c.slug = ? ', $this->getRequestParameter('cat'));
    }
    
    $q->addWhere($q->getRootAlias().'.published = ?', true);

    $q->orderBy($rootAlias.'.published_at desc');

    return $q;
  }
  
  public function buildParams()
  {
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
  
  public function executeTagSidebar($request)
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = TagTable::findOrCreateByTagname($this->getRequestParameter('tag'));
    }
    
    $this->popular = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true, 'limit' => 10));

    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost'));
    
    $this->categories = Doctrine::getTable('aBlogCategory')
      ->createQuery('c')
      ->orderBy('c.name')
      ->execute();
  }
}
