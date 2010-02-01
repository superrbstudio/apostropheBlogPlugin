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
  
  protected function buildQuery($parameters)
  {
    $filter = new aBlogPostFormFilter();
    $filter->bind($parameters);
    return $filter->getQuery();
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $pager = new sfDoctrinePager('aBlogPost', 10);
    $pager->setQuery(Doctrine::getTable('aBlogPost')->createQuery());
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->pager = $pager; 
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->a_blog_post = $this->getRoute()->getObject();
  }
  
}
