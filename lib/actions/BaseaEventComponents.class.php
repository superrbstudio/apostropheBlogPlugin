<?php

/**
 * Base Components for the apostropheBlogPlugin aEvent module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aEvent
 * @author      Dan Ordille
 */
abstract class BaseaEventComponents extends sfComponents
{
  protected $modelClass = 'aEvent';

  public function setup()
  {
    parent::setup();
    if(sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

}
