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
    $this->redirect('@a_event_admin_edit?slug='.$this->a_event->getSlug());
  }
    
  public function executeAutocomplete(sfWebRequest $request)
  {
    $search = $request->getParameter('search', '');
    $this->aEvents = Doctrine::getTable('aEvent')->createQuery()
      ->andWhere("title LIKE ?", '%'.$search.'%')
      ->execute(array(), Doctrine::HYDRATE_ARRAY);
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
        $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $this->a_event)));
      }
      $this->setLayout(false);
      return $this->renderPartial('aEventAdmin/form', array('a_event' => $this->a_event, 'form' => $this->form, 'dog' => '1'));
    }
    else
    {
      $this->processForm($request, $this->form);
    }
    $this->setTemplate('edit');
  }
  
}