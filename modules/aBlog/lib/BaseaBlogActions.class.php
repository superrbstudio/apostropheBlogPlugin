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
  protected function buildQuery($parameters = array())
  {
    $filter = new aBlogPostFormFilter(array(), array(), false);
    $filter->bind($parameters);
    return $filter->getQuery();
  }
  
  public function executeIndex(sfWebRequest $request)
  {
    $this->blogCategories = aTools::getCurrentPage()->BlogCategories;
    $parameters['categories_list'] = array_map(create_function('$a', 'return $a["id"];'),  $this->blogCategories->toArray());
    $pager = new sfDoctrinePager('aBlogPost', 10);
    $pager->setQuery($this->buildQuery($parameters));
    $pager->setPage($this->getRequestParameter('page', 1));
    $pager->init();
    
    $this->pager = $pager; 
  }
  
  public function executeShow(sfWebRequest $request)
  {
    $this->a_blog_post = $this->getRoute()->getObject();
  }
  
}
