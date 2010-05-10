<?php
require_once dirname(__FILE__).'/aBlogCategoryAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogCategoryAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogCategoryAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogCategoryAdmin
 * @author      Your name here
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BaseaBlogCategoryAdminActions extends autoaBlogCategoryAdminActions
{
  public function executePosts()
  {
    $this->redirect('@a_blog_admin');
  }

  public function executeEvents()
  {
    $this->redirect('@a_event_admin');
  }
 
}
