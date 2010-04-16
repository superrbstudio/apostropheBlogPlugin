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
    $this->redirect('aBlogAdmin/edit?slug='.$this->a_blog_post->getSlug());
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    $search = $request->getParameter('search', '');
    $this->aBlogPosts = Doctrine::getTable('aBlogPost')->createQuery()
      ->andWhere("title LIKE ?", '%'.$search.'%')
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
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
      if($request->getParameter('field', false))
      {
        return $this->renderPartial('aBlogAdmin/form_'.$request->getParameter('field'),
          array('a_blog_post' => $this->a_blog_post, 'form' => $this->form));
      }
      return $this->renderPartial('aBlogAdmin/form', array('a_blog_post' => $this->a_blog_post, 'form' => $this->form));
    }
    else
    {
      $this->processForm($request, $this->form);
    }
    $this->setTemplate('edit');
  }
  
}
