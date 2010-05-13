<?php

class apostropheBlogPluginEngineActions extends aEngineActions
{
  /**
   * Executes today action
   *
   * Forwards to the index action with today's date as request params.
   */
  public function executeToday(sfWebRequest $request)
  {
    $request->setParameter('day', date('d'));
    $request->setParameter('month', date('m'));
    $request->setParameter('year', date('Y'));

    $this->forward($this->getModuleName(), 'index');
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
  
  public function nofollowIfNeeded()
  {
    $request = $this->getRequest();
    
    // Ask spiders not to follow further links once they reach category, tag, or search
    // result pages or start browsing by year. This allows spidering of the first page of results 
    // for a tag or category, which is good SEO, but not an infinite spidering of every 
    // possible filter combination
  
    $nofollow = array('cat', 'tag', 'search', 'year');
    foreach ($nofollow as $arg)
    {
      if (strlen($request->getParameter($arg)))
      {
        $this->getResponse()->addMeta('robots', 'noarchive, nofollow');
        break;
      }
    }
  }
}