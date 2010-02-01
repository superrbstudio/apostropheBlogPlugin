<?php

/**
 * PluginaBlogItem form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaBlogItemFormFilter extends BaseaBlogItemFormFilter
{
  
  public function configure()
  {
    //$this->widgetSchema->setLabel('editors_list', 'Edited By');
    //$this->widgetSchema->setLabel('user_id', 'By');
  }
  
  public function filterSet($name)
  {
    if($this[$name]->getValue() === null || $this[$name]->getValue() === '')
    {
      return false;
    }
    if(is_array($name) && !count($name))
    {
      return false;
    }
   
    return true;
  }
  
  public function getAppliedFilters()
  {
    $fields = $this->getFields();
    $filters = array();
    foreach($this as $field)
    {
      $value = $this->getFilterValue($field);
      if(!is_null($value) && $value != '')
      {
        $filters[$field->getName()] = $value;
      }
    }
    return $filters;
  }
  
  
  
}
