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
      $response = array();
      $response['aBlogPost'] = $this->a_blog_post->toArray();
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

  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    if (is_null($this->filters))
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $this->filters->setTableMethod($tableMethod);

    $query = $this->filters->buildQuery($this->getFilters());

    $this->addSortQuery($query);

    if(!$this->getUser()->hasCredential('admin'))
    {
      Doctrine::getTable('aBlogPost')->filterByEditable($query, $this->getUser()->getGuardUser()->getId());
    }

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);
    $query = $event->getReturnValue();

    return $query;
  }

  protected function executeBatchPublish(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('aBlogPost')
      ->whereIn('id', $ids)
      ->execute();
    $count = count($items);
    $error = false;
    try
    {
      foreach($items as $item)
      {
        $item->publish();
      }
      $items->save();
    } catch (Exception $e)
    {
      $error = true;
    }

    if (($count == count($ids)) && (!$error))
    {
      $this->getUser()->setFlash('notice', 'The selected items have been published successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'An error occurred while publishing the selected items.');
    }

    $this->redirect('@a_blog_admin');
  }

  protected function executeBatchUnpublish(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $items = Doctrine_Query::create()
      ->from('aBlogPost')
      ->whereIn('id', $ids)
      ->execute();
    $count = count($items);
    $error = false;
    try
    {
      foreach($items as $item)
      {
        $item->unpublish();
      }
      $items->save();
    } catch (Exception $e)
    {
      $error = true;
    }

    if (($count == count($ids)) && (!$error))
    {
      $this->getUser()->setFlash('notice', 'The selected items have been unpublished successfully.');
    }
    else
    {
      $this->getUser()->setFlash('error', 'An error occurred while unpublishing the selected items.');
    }

    $this->redirect('@a_blog_admin');
  }
  
}
