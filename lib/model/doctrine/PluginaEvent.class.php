<?php

/**
 * PluginaEvent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginaEvent extends BaseaEvent
{
  public $engine = 'aEvent';
  
  public function getTemplateDefaults()
  {
    return array(
      'singleColumnTemplate' => array(
        'name' => 'Single Column',
        'areas' => array('blog-body')
      )
    );
  }
  
  public function getVirtualPageSlug()
  {
    return '@a_event_search_redirect?id=' . $this->id;
  }
}
