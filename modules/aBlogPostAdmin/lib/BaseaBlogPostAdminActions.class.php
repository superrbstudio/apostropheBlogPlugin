<?php

require_once dirname(__FILE__).'/aBlogPostAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogPostAdminGeneratorHelper.class.php';

/**
 * aBlogPostAdmin actions.
 *
 * @package    apostropheBlogPlugin
 * @subpackage aBlogPostAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseaBlogPostAdminActions extends autoABlogPostAdminActions
{
  public function executePublish(sfWebRequest $request)
  {
    $post = Doctrine::getTable('aBlogPost')->find($request->getParameter('id'));
    
    $this->forward404Unless($post instanceof aBlogPost);
    
    $published = ($post->togglePublish()) ? 'published' : 'unpublished';
        
    $post->save();

    $this->getUser()->setFlash('notice', 'This post was '. $published .' successfully.');
 
    $this->redirect('@a_blog_post_admin_edit?id='. $post->getId());
  }
  
  public function executeMedia(sfWebRequest $request)
  {
    $post = Doctrine::getTable('aBlogPost')->find($request->getParameter('id'));
    
    $url = 'aMedia/select?' .
         http_build_query(array(
             'multiple' => true,
             'aMediaIds' => implode(',', $post->getAttachedMediaIds()),
             'after' => 'aBlogPostAdmin/attach?id='.$post->getId()
           ));
           
    $this->redirect($url);
  }

	public function executeAttach(sfWebRequest $request)
	{
	  $this->post = $this->getRoute()->getObject();
	  $ids = preg_split('/,/', $request->getParameter('aMediaIds'));

    // Note that this serves as validation that they are real media ids
    $items = Doctrine::getTable('aMediaItem')->findByIdsInOrder($ids);
    
    if (!empty($items))
    {
      // We're keeping a format similar to the old media plugin format until the change to using areas.
      // If we weren't retiring this fork I'd do a real migration and join with a refClass etc.
      $nitems = array();
      foreach ($items as $item)
      {
        // Serialize fake objects instead of expensive Doctrine objects
        $nitems[] = (object) array('id' => $item->id);
      }
      $this->post->setMedia(serialize($items));
      $count = count($items);
    }
    else
    {      
      $this->post->setMedia('');
      $count = 0;
    }
    
    $this->post->save();
    
    $notice = $count .' media ';
    $notice .= ($count > 1 || $count == 0) ? 'items were' : 'item was';
    $notice .= ' attached to your post.';
    
    $this->getUser()->setFlash('notice', $notice);

    return $this->redirect('@a_blog_post_admin');
	}
  
  public function executeSaveAndPublish(sfWebRequest $request)
  {
    $result = parent::executeCreate($request);
    
    $this->a_blog_post->togglePublish();
    
    return $result;
  }

  public function executeBatchPublish(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');
 
    $q = Doctrine_Query::create()
      ->from('aBlogPost p')
      ->whereIn('p.id', $ids);
 
    foreach ($q->execute() as $post)
    {
      $post->togglePublish();
      
      $post->save();
    }
 
    $this->getUser()->setFlash('notice', 'The selected posts have been published/unpublished successfully.');
 
    $this->redirect('@a_blog_post_admin');
  }
  
}
