<?php

require_once dirname(__FILE__).'/aBlogEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogEventAdminGeneratorHelper.class.php';

/**
 * aBlogEventAdmin actions.
 *
 * @package    apostropheBlogPlugin
 * @subpackage aBlogEventAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseaBlogEventAdminActions extends autoABlogEventAdminActions
{
  public function executePublish(sfWebRequest $request)
  {
    $event = Doctrine::getTable('aBlogEvent')->find($request->getParameter('id'));
    
    $this->forward404Unless($event instanceof aBlogEvent);
    
    $published = ($event->togglePublish()) ? 'published' : 'unpublished';
        
    $event->save();

    $this->getUser()->setFlash('notice', 'This event was '. $published .' successfully.');
 
    $this->redirect('@a_blog_event_admin_edit?id='. $event->getId());
  }
  
  public function executeMedia(sfWebRequest $request)
  {
    $event = Doctrine::getTable('aBlogEvent')->find($request->getParameter('id'));
    
    $url = sfConfig::get('app_a_media_site', false)
         . "/media/select?"
         . http_build_query(array(
             'multiple' => true,
             'aMediaIds' => implode(',', $event->getAttachedMediaIds()),
             'after' => 'aBlogEventAdmin/attach?id='.$event->getId()
           ));
           
    $this->redirect($url);
  }

	public function executeAttach(sfWebRequest $request)
	{
	  $this->event = $this->getRoute()->getObject();
    $items = aMediaAPI::getSelectedItems($request, false, false);
    
    if (!$items === false)
    {
      $this->event->setMedia(serialize($items));
    }
    else
    {
      $this->event->setMedia('');
    }
    
    $this->event->save();
    
    $notice = count($items) .' media ';
    $notice .= (count($items) > 1) ? 'items were' : 'item was';
    $notice .= ' attached to your event.';
    
    $this->getUser()->setFlash('notice', $notice);

    return $this->redirect('@a_blog_event_admin');
	}

  public function executeSaveAndPublish(sfWebRequest $request)
  {
    $result = parent::executeCreate($request);
    
    $this->a_blog_event->togglePublish();
    
    return $result;
  }

  public function executeBatchPublish(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
 
    $q = Doctrine_Query::create()
      ->from('aBlogEvent p')
      ->whereIn('p.id', $ids);
 
    foreach ($q->execute() as $event)
    {
      $event->togglePublish();
      
      $event->save();
    }
 
    $this->getUser()->setFlash('notice', 'The selected events have been published/unpublished successfully.');
 
    $this->redirect('@a_blog_event_admin');
  }
  
}
