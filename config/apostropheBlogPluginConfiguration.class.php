<?php

/**
 * aBlogPlugin configuration.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class apostropheBlogPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('a.getGlobalButtons', array('apostropheBlogPluginConfiguration', 
      'getGlobalButtons'));
  }
  
  static public function getGlobalButtons()
  {
    $user = sfContext::getInstance()->getUser();
    if ($user->hasCredential('blog_author') || $user->hasCredential('blog_admin'))
    {
      aTools::addGlobalButtons(array(
        new aGlobalButton('blog', 'Blog', '@a_blog_admin', 'a-blog-btn'),
        new aGlobalButton('events', '<span class="day"></span> Events', 'aBlogEventAdmin/index', 'a-events day-'.date('j'))
      ));
    }
  }
}
