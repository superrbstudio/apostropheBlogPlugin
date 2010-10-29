<?php

/**
 * Base actions for the aBlogPlugin aBlog module.
 *
 * @package     aBlogPlugin
 * @subpackage  aBlog
 * @author      P'unk Avenue
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogActions extends aEngineActions
{
  protected $modelClass = 'aBlogPost';

  public function preExecute()
  {
    parent::preExecute();
    $this->categories = aCategoryTable::getCategoriesForPage($this->page);
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  protected function filterByPageCategory()
  {
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c');
    Doctrine::getTable($this->modelClass)->filterByCategories($this->categories, $q);
    
    return $q;
  }

  protected function buildQuery($request)
  {
    $q = $this->filterByPageCategory();

    if($request->hasParameter('year'))
      Doctrine::getTable($this->modelClass)->filterByYMD($request->getParameter('year'), $request->getParameter('month'), $request->getParameter('day'), $q);
    if($request->hasParameter('cat'))
      Doctrine::getTable($this->modelClass)->filterByCategory($request->getParameter('cat'), $q);
    if($request->hasParameter('tag'))
      Doctrine::getTable($this->modelClass)->filterByTag($request->getParameter('tag'), $q);
    Doctrine::getTable($this->modelClass)->addPublished($q);
    $q->orderBy('published_at desc');

    return $q;
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->buildParams();
    if ($request->hasParameter('max_per_page'))
    {
      $this->getUser()->setAttribute('max_per_page', $request->getParameter('max_per_page'), 'apostropheBlog_prefs');
    }
    $this->max_per_page = $this->getUser()->getAttribute('max_per_page', 20, 'apostropheBlog_prefs');
    $pager = new sfDoctrinePager($this->modelClass);
    $pager->setMaxPerPage($this->max_per_page);
    $pager->setQuery($this->buildQuery($request));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();

    $this->pager = $pager;

    aBlogItemTable::populatePages($pager->getResults());

    if($request->hasParameter('year') || $request->hasParameter('month') || $request->hasParameter('day') || $request->hasParameter('cat') || $request->hasParameter('tag'))
    {
      $this->getResponse()->addMeta('robots', 'noarchive, nofollow');
    }

    if($this->getRequestParameter('feed', false))
    {
      $this->getFeed();
      return sfView::NONE;
    }

  }

  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aBlogPost = $this->getRoute()->getObject();
		$this->categories = aCategoryTable::getCategoriesForPage($this->page);
    $this->forward404Unless($this->aBlogPost);
    $this->forward404Unless($this->aBlogPost['status'] == 'published' || $this->getUser()->isAuthenticated());
		$this->preview = $this->getRequestParameter('preview');
    aBlogItemTable::populatePages(array($this->aBlogPost));
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
    $this->addFilterParams('max_per_page');
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
    aBlogItemTable::populatePages($this->articles);

    $title = sfConfig::get('app_aBlog_feed_title', $this->page->getTitle());

    $this->feed = sfFeedPeer::createFromObjects(
      $this->articles,
      array(
        'format'      => 'rss',
        'title'       => $title,
        'link'        => '@a_blog',
        'authorEmail' => sfConfig::get('app_aBlog_feed_author_email'),
        'authorName'  => sfConfig::get('app_aBlog_feed_author_name'),
        'routeName'   => '@a_blog_post',
        'methods'     => array('description' => 'getFeedText')
      )
    );

    $this->getResponse()->setContent($this->feed->asXml());
  }
  
  public function executeSearch(sfWebRequest $request)
  {
    $this->buildParams();
    
    $now = date('YmdHis');
    
    // create the array of pages matching the query
    
    $q = $request->getParameter('q');
    if ($request->hasParameter('x'))
    {
      // We sometimes like to use input type="image" for presentation reasons, but it generates
      // ugly x and y parameters with click coordinates. Get rid of those and come back.
      return $this->redirect(sfContext::getInstance()->getController()->genUrl('aBlog/search', true) . '?' . http_build_query(array("q" => $q)));
    }
    
    $key = strtolower(trim($q));
    $key = preg_replace('/\s+/', ' ', $key);
    $replacements = sfConfig::get('app_a_search_refinements', array());
    if (isset($replacements[$key]))
    {
      $q = $replacements[$key];
    }

    try
    {
      $q = "($q) AND slug:@a_blog_redirect";
      $values = aZendSearch::searchLuceneWithValues(Doctrine::getTable('aPage'), $q, aTools::getUserCulture());
    } catch (Exception $e)
    {
      // Lucene search error. TODO: display it nicely if they are always safe things to display. For now: just don't crash
      $values = array();
    }
    $nvalues = array();

    // The truth is that Zend cannot do all of our filtering for us, especially
    // permissions-based. So we can do some other filtering as well, although it
    // would be bad not to have Zend take care of the really big cuts (if 99% are
    // not being prefiltered by Zend, and we have a Zend max results of 1000, then 
    // we are reduced to working with a maximum of 10 real results).

    foreach ($values as $value)
    {
      // 1.5: the names under which we store columns in Zend Lucene have changed to
      // avoid conflict with also indexing them
      $info = unserialize($value->info_stored);
      
      // Filtering categories this way is not ideal, we could drown in 1000 results for
      // an unrelated category and not get a chance to winnow out the handful for this
      // category. Think about a way to get Lucene to do it. However that is tricky 
      // because we do a lot of gnarly things like merging categories
      if (count($this->page->getCategories()))
      {
        $good = false;
        $categories = aArray::listToHashById($this->page->getCategories());
        $ids = preg_split('/,/', $value->category_ids);
        foreach ($ids as $id)
        {
          if (isset($categories[$id]))
          {
            $good = true;
          }
        }
        if (!$good)
        {
          continue;
        }
      }
      if ($value->published_at > $now)
      {
        continue;
      }
      if (!aPageTable::checkPrivilege('view', $info))
      {
        continue;
      }
      $nvalue = $value;
      $nvalue->slug = $nvalue->slug_stored;
      $nvalue->title = $nvalue->title_stored;
      $nvalue->summary = $nvalue->summary_stored;
      // Virtual page slug is a named Symfony route, it wants search results to go there
      $nvalue->url = $this->getController()->genUrl($nvalue->slug, true);
      $nvalue->class = 'aBlog';
      $nvalues[] = $nvalue;
    }
    $values = $nvalues;
    $this->pager = new aArrayPager(null, sfConfig::get('app_a_search_results_max_per_page', 10));    
    $this->pager->setResultArray($values);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    $this->pagerUrl = "aBlog/search?" . http_build_query(array("q" => $q));
    // setTitle takes care of escaping things
    $this->getResponse()->setTitle(aTools::getOptionI18n('title_prefix') . 'Search for ' . $q . aTools::getOptionI18n('title_suffix'));
    $this->results = $this->pager->getResults();
  }
}
