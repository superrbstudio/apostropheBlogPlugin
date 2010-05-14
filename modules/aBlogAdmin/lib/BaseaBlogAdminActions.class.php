<?php
require_once dirname(__FILE__).'/aBlogAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaBlogAdminActions extends autoABlogAdminActions
{


  public function executeNew(sfWebRequest $request)
  {
    $this->a_blog_post = new aBlogPost();
    $this->a_blog_post->Author = $this->getUser()->getGuardUser();
    $this->a_blog_post->save();
    $this->redirect('a_blog_admin_edit', $this->a_blog_post);
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    $search = $request->getParameter('q', '');
    $q = Doctrine::getTable('aBlogPost')->createQuery()
      ->andWhere("title LIKE ?", '%'.$search.'%');
    Doctrine::getTable('aBlogPost')->addPublished($q);
    $this->aBlogPosts =  $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->a_blog_post = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->a_blog_post);

    if($request->isXmlHttpRequest())
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_blog_post = $this->form->save();
        //We need to recreate the form to handle the fact that it is not possible to change the value of a sfFormField
        $this->form = $this->configuration->getForm($this->a_blog_post);
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_blog_post)));
      }
      $this->setLayout(false);
      $response = array();
      aBlogItemTable::populatePages(array($this->a_blog_post));
      $response['aBlogPost'] = $this->a_blog_post->toArray();
      // We need to decode the title because jQuery will be stuffing it in with .value, which
      // doesn't need the escaping
      $response['aBlogPost']['title'] = html_entity_decode($response['aBlogPost']['title'], ENT_COMPAT, 'UTF-8');
      $response['modified'] = $this->a_blog_post->getLastModified();
      //Any additional messages can go here
      $output = json_encode($response);
      $this->getResponse()->setHttpHeader("X-JSON", '('.$output.')');
      return sfView::HEADER_ONLY;
    }
    else
    {
      $this->processForm($request, $this->form);
    }
    $this->setTemplate('edit');
  }

  public function executeRedirect()
  {
    $aBlogPost = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aBlogPost->findBestEngine());
    $this->redirect($this->generateUrl('a_blog_post', $this->getRoute()->getObject()));
  }

  public function executeCategories()
  {
    $this->redirect('@a_blog_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aBlog'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
    parent::executeEdit($request);
    aBlogItemTable::populatePages(array($this->a_blog_post));
  }

  protected function buildQuery()
  {
    $query = parent::buildQuery();
    $query->leftJoin($query->getRootAlias().'.Author')
      ->leftJoin($query->getRootAlias().'.Editors')
      ->leftJoin($query->getRootAlias().'.Categories')
      ->leftJoin($query->getRootAlias().'.Page');
    return $query;
  }
}
