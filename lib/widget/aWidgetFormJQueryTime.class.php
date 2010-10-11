<?php

class aWidgetFormJQueryTime extends sfWidgetFormInputText
{

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('format', 'g:iA');

  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The time displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if(!empty($value))
      $value = date($this->getOption('format'), strtotime($value));

    $attributes['id'] = $this->generateId($name);
    $html = parent::render($name, $value, $attributes, $errors);
    $html.= "<script type='text/javascript'>timepicker2('#".$attributes['id']."', {'minutes-increment' : 30, 'twenty-four-hour' : true})</script>";

    return $html;
  }
}