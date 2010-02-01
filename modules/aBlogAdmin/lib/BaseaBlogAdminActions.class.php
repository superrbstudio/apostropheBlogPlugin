<?php
require_once dirname(__FILE__).'/aBlogAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogAdminActions extends autoABlogAdminActions
{
  
  protected function buildQuery()
  {
    $query = parent::buildQuery();
     
    $query->andWhere("author_id = ?", $this->getUser()->getGuardUser()->getId());
    
    return $query;
  }
  
  
  public function executeNew(sfWebRequest $request)
  {
    
    $this->a_blog_post = new aBlogPost();
    $this->a_blog_post->Author = $this->getUser()->getGuardUser();
    $this->a_blog_post->save();
    
    //Get global page
    $this->page = aPageTable::retrieveBySlugWithSlots('global');
    //$this->body = $this->page->createSlot($this->type);
    
    
    $this->form = $this->configuration->getForm($this->a_blog_post);
    $this->a_blog_post = $this->form->getObject();
    
  }
  
}
