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
  protected $slugStem = '@a_blog_search_redirect';
  // The application of the various filters (date, category, tag, search) is done
  // by aBlogToolkit::filterForEngine. That's where the important bits are

  public function getFilterForEngineParams()
  {
    $request = $this->getRequest();

    $options = array(
      'q' => $request->getParameter('q'),
      'categoryIds' => aArray::getIds($this->page->Categories),
      'categorySlug' => $request->getParameter('cat'),
      'author' => $request->getParameter('author'),
      'tag' => $request->getParameter('tag'),
      'slugStem' => $this->slugStem,
      'year' => $request->getParameter('year'),
      'month' => $request->getParameter('month'),
      'day' => $request->getParameter('day'),
      'byPublishedAt' => true);
    // For the show action we don't want to limit the filters
    // to posts for the same day, it's only in the URL for show
    if ($request->getParameter('action') === 'show')
    {
      unset($options['year']);
      unset($options['month']);
      unset($options['day']);
    }

    // The request is now available to this event so it can parse more options from it
    $options = sfContext::getInstance()->getEventDispatcher()->filter(new sfEvent(null, 'aBlog.filterForEngineParams', array('request' => $request)), $options)->getReturnValue();

    return $options;
  }

  public function preExecute()
  {
    parent::preExecute();
    $request = $this->getRequest();
    $this->info = aBlogToolkit::filterForEngine($this->getFilterForEngineParams());
  }

  /**
   * $request is the web request, for historical bc reasons. $options can 
   * contain 'blogItemsOnly' => true to avoid returning the associated pages, useful
   * when we are just making a calendar with no titles etc. Also 'pageIds' => array()
   * to request specific page IDs rather than those in $this->info['pageIds']
   * (used by the new app_aBlog_arrayPager performance tweak).
   */
  protected function buildQuery($request, $options = array())
  {
    // We already know what page ids are relevant, now we're fetching the blog items
    // themselves as well as author information etc. and bringing it all into
    // Doctrine land. There's another method implicitly called later to populate
    // all of the Apostrophe content for the posts
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a');
    $blogItemsOnly = isset($options['blogItemsOnly']) && $options['blogItemsOnly'];
    $pageIds = isset($options['pageIds']) ? $options['pageIds'] : $this->info['pageIds'];
    if (count($pageIds))
    {
      // We have page ids, so we need a join to figure out which blog items we want.
      // Doctrine doesn't have a withIn mechanism that takes a nice clean array, but we
      // know these are clean IDs
      $q->innerJoin($this->modelClass.'.Page p WITH p.id IN (' . implode(',', $pageIds) . ')');
      $q->leftJoin($this->modelClass.'.Categories c');
      // Oops: there is NO ordering with an IN clause alone, you must make that explicit
      if ($blogItemsOnly)
      {
        $q->select($q->getRootAlias() . '.*');
      }
      aDoctrine::orderByList($q, $pageIds, 'p');
      // When you call aDoctrine::orderByList you must have an explicit select clause of your own as the
      // default 'select everything' behavior of Doctrine goes away as soon as that method calls addSelect
      if (!$blogItemsOnly)
      {
        $q->addSelect($q->getRootAlias() . '.*, a.*, p.*, c.*');
      }
    }
    else
    {
      $q->where('0 <> 0');
    }
    return $q;
  }

  protected function getBlogItemsForPageIds($pageIds)
  {
    $query = $this->buildQuery($this->getRequest(), array('pageIds' => $pageIds));
    return $query->execute();
  }

  public function getMaxPerPage()
  {
    return $this->getUser()->getAttribute('max_per_page', $this->getDefaultMaxPerPage(), 'apostropheBlog_prefs');
  }

  public function getDefaultMaxPerPage()
  {
    return sfConfig::get('app_aBlog_max_per_page', 20);
  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->buildParams();
    $this->max_per_page = $this->getMaxPerPage();

    // Dramatically faster with a large database of blog posts. Leverages the
    // knowledge that we already have an array of page ids, we're not adding any
    // new criteria to the query (just populating more joins that don't 
    // change the result), and sending huge arrays of page IDs back and
    // forth to MySQL is a slow operation. Configured via app.yml to avoid
    // breaking old templates that expect to call getResults() directly and
    // would be unpleasantly surprised to get back IDs. Instead you should
    // use the $results template variable. Make sure you get the raw version

    if (sfConfig::get('app_aBlog_arrayPager'))
    {
      $pager = new aArrayPager();
      $pager->setMaxPerPage($this->max_per_page);
      $pager->setResultArray($this->info['pageIds']);
      $pager->setPage($this->getRequestParameter('page', 1));
      $pager->init();

      $this->pager = $pager;
      $pageIds = $pager->getResults();
      $this->results = $this->getBlogItemsForPageIds($pageIds);
    }
    else
    {
      $pager = new sfDoctrinePager($this->modelClass);
      $pager->setMaxPerPage($this->max_per_page);
      $pager->setQuery($this->buildQuery($request));
      $pager->setPage($this->getRequestParameter('page', 1));
      $pager->init();

      $this->pager = $pager;

      $this->results = $pager->getResults();
    }

    // $start = microtime(true);
    aBlogItemTable::populatePages($this->results);
    // error_log('populatePages: ' . sprintf('%.2f', microtime(true) - $start));

    if($request->hasParameter('year') || $request->hasParameter('month') || $request->hasParameter('day') || $request->hasParameter('cat') || $request->hasParameter('tag'))
    {
      // Forbid combinations of filters for bots like Google. This prevents aggressive overspidering
      // of the same data
      $this->getResponse()->addMeta('robots', 'noarchive, nofollow');
    }

    if($this->getRequestParameter('feed', false))
    {
      $this->getFeed();
      return sfView::NONE;
    }

    return $this->pageTemplate;
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->buildParams();
    $this->dateRange = '';
    $this->aBlogPost = $this->getRoute()->getObject();
    $this->forward404Unless($this->aBlogPost);
    $this->forward404Unless($this->aBlogPost['status'] == 'published' || $this->getUser()->isAuthenticated());
    $this->preview = $this->getRequestParameter('preview');
    aBlogItemTable::populatePages(array($this->aBlogPost));

    // Thanks to Giles Smith for catching that we had no titles on our blog post permalink pages!
    // Too much Chrome will do that to you (:
    // Title is pre-escaped as valid HTML
    $prefix = aTools::getOptionI18n('title_prefix');
    $suffix = aTools::getOptionI18n('title_suffix');
    $this->getResponse()->setTitle($prefix . $this->aBlogPost->Page->getTitle() . $suffix, false);

    return $this->pageTemplate;
  }

  /**
   * Date-related filter parameters
   */

  public function buildParams()
  {
    $request = $this->getRequest();
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

    // set our parameters for building links that set the date ranges and
    // keep other filters alive as well
    $this->params['day'] = array('year' => date('Y', $date), 'month' => date('m', $date), 'day' => date('d', $date));
    $this->params['month'] = array('year' => date('Y', $date), 'month' => date('m', $date));
    $this->params['year'] = array('year' => date('Y', $date));
    $this->params['nodate'] = array();

    // Now add all of the non-date-based parameters to each key in params
    $this->addFilterParams('cat');
    $this->addFilterParams('tag');
    $this->addFilterParams('author');
    $this->addFilterParams('q');
    foreach ($this->info['extraFilterCriteria'] as $extraFilterCriterion)
    {
      $this->addFilterParams($extraFilterCriterion['urlParameter']);
    }
    // For backwards compatibility with overrides of indexSuccess, we have to
    // make this available via $this->params in order for it to reach _filters.php
    $this->params['extraFilterCriteria'] = $this->info['extraFilterCriteria'];

    // Listeners must add their custom criteria for every key in the passed array,
    // like the addFilterParams method does
    $this->params = sfContext::getInstance()->getEventDispatcher()->filter(
      new sfEvent(null, 'aBlog.filterParams'), $this->params)->getReturnValue();
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
    $title = sfConfig::get('app_aBlog_feed_title', $this->page->getTitle());
    if(!isset($this->results))
    {
      $this->results = $this->pager->getResults();
    }
    $feedParameters = array(
      'format'      => 'rss',
      'title'       => $title,
      'link'        => '@a_blog',
      'authorEmail' => sfConfig::get('app_aBlog_feed_author_email'),
      'authorName'  => sfConfig::get('app_aBlog_feed_author_name'),
      'methods'     => array('description' => 'getFeedText', 'title' => 'getFeedTitle'),
      'routeName' => '@a_blog_post'
    );
    $event = new sfEvent(null, 'aBlog.filterFeedParameters');
    $this->dispatcher->filter($event, $feedParameters);
    $feedParameters = $event->getReturnValue();
    $this->feed = sfFeedPeer::createFromObjects(
      $this->results,
      $feedParameters
    );

    $this->getResponse()->setContent($this->feed->asXml());
  }
}
