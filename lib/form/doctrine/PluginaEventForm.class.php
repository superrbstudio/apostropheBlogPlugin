<?php

/**
 * PluginaEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaEventForm extends BaseaEventForm
{

  public function setup()
  {
    parent::setup();
    
    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }
}
