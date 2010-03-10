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
  
  public function setup()
  {
    $this->fields = $this->getFields();
    parent::setup();
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
    $values = $this->processValues($this->getDefaults());
    $fields = $this->getFields();
    
    $names = array_merge($fields, array_diff(array_keys($this->validatorSchema->getFields()), array_keys($fields)));
    $fields = array_merge($fields, array_combine($names, array_fill(0, count($names), null)));
    
    $appliedValues = array();
    
    foreach ($fields as $field => $type)
    {
      if (!isset($values[$field]) || null === $values[$field] || '' === $values[$field] || $field == $this->getCSRFFieldName())
      {
        continue;
      }
      
      $method = sprintf('get%sValue', self::camelize($this->getFieldName($field)));
      if (method_exists($this, $method))
      {
        $value = $this->$method($field, $values[$field]);
        if($value) $appliedValues[$field] = $value; 
      }
      else if (null != $type)
      {
        $method = sprintf('get%sValue', $type);
        if (method_exists($this, $method = sprintf('get%sValue', $type)))
        {
          $value = $this->$method($field, $values[$field]);
          if($value) $appliedValues[$field] = $value; 
        }
        
      }
    }
    return $appliedValues; 
  }
  
  protected function getManyKeyValue($field, $values)
  {
    return $this->getForeignKeyValue($field, $values);
  }
  
  protected function getForeignKeyValue($field, $values)
  {
    $appliedValues = array();
    $choices = $this[$field]->getWidget()->getChoices();
    if(is_array($values))
    {
      foreach($values as $value)
      {
        $appliedValues[] = $choices[$value]; 
      }
    }
    else
    {
      $appliedValues[] = $choices[$values];
    }
    return $appliedValues;
  }
  
  protected function getNumberValue($field, $values)
  {
    if(is_array($values) && isset($values['text']) && '' !== $values['text'])
    {
      return $values['text'];
    }
  }
  
  protected function getEnumValue($field, $value)
  {
    return array($value);
  }
  
  protected function getBooleanValue($field, $value)
  {
    $choices = $this->getWidget($field)->getChoices();
    return array($choices[$value]);
  }
}
