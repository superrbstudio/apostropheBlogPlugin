<?php
require_once dirname(__FILE__).'/aEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aEventAdminGeneratorHelper.class.php';
/**
 * Base actions for the aEventPlugin aEventAdmin module.
 * 
 * @package     aEventPlugin
 * @subpackage  aEventAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaEventAdminActions extends autoAEventAdminActions
{ 

  public function executeNew(sfWebRequest $request)
  {
    $this->a_event = new aEvent();
    $this->a_event->Author = $this->getUser()->getGuardUser();
    $this->a_event->save();
    $this->redirect('a_event_admin_edit',$this->a_event);
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    $search = $request->getParameter('q', '');
    $q = Doctrine::getTable('aEvent')->createQuery()
      ->andWhere("title LIKE ?", '%'.$search.'%');
    Doctrine::getTable('aEvent')->addPublished($q);
    $this->aEvents = $q->execute();
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    $this->a_event = $this->getRoute()->getObject();
    $this->form = $this->configuration->getForm($this->a_event);

    if($request->isXmlHttpRequest())
    {
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_event = $this->form->save();
        //We need to recreate the form to handle the fact that it is not possible to change the value of a sfFormField
        $this->form = $this->configuration->getForm($this->a_event);
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_event)));
      }
      $this->setLayout(false);
      $response = array();
      $response['aBlogPost'] = $this->a_event->toArray();
      $response['modified'] = $this->a_event->getLastModified();
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
    $aEvent = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aEvent->findBestEngine());
    $this->redirect($this->generateUrl('a_event', $this->getRoute()->getObject()));
  }
  
}