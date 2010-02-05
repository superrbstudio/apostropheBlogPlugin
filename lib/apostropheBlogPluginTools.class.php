<?php

class apostropheBlogPluginTools
{
  // You too can do this in a plugin dependent on a, see the provided stylesheet 
  // for how to correctly specify an icon to go with your button. See the 
  // apostropheMediaCMSSlotsPluginConfiguration class for the registration of the event listener.
  static public function getGlobalButtons()
  {
    $user = sfContext::getInstance()->getUser();
    if ($user->hasCredential('blog_author') || $user->hasCredential('blog_admin'))
    {
      aTools::addGlobalButtons(array(
        new aGlobalButton('Blog', 'aBlogPostAdmin/index', 'a-blog-btn'),
  			new aGlobalButton('Events', 'aBlogEventAdmin/index', 'a-events day-'.date('j'))
  		));
  	}
  }
}
