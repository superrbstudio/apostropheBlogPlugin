<?php

require_once dirname(__FILE__).'/../lib/BaseaBlogAdminActions.class.php';

/**
 * aBlogAdmin actions.
 * 
 * @package    aBlogPlugin
 * @subpackage aBlogAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class aBlogAdminActions extends BaseaBlogAdminActions
{
  public function executeIndex(sfWebRequest $request)
  {
    // sorting
    if ($request->getParameter('sort'))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }
    
    // If get parameters exist assume filters should be reset
    if(count($request->getGetParameters()))
    {
      $this->setFilters($this->configuration->getFilterDefaults());

      $this->filters = new aBlogPostFormFilter();
      $this->filters->bind($request->getParameter('blog'));
      $this->setFilters($this->filters->processValues($this->filters->getValues()));
    }
    
    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    aTools::setAllowSlotEditing(false);
  }
  
}
