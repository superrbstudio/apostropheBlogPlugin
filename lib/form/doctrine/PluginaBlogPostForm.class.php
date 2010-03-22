<?php

/**
 * PluginaBlogPost form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogPostForm extends BaseaBlogPostForm
{
  public function setup()
  {
    parent::setup();
    
    $this->setWidget('published_at', new sfWidgetFormJQueryDateTime(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')
		));
  }  

}
