<?php


class sfWidgetFormJQueryDateTime extends sfWidgetForm
{
  
  protected $dateWidget;
  protected $timeWidget;
    
  protected function configure($dateOptions = array(), $dateAttributes = array(), $timeOptions = array(), $timeAttributes = array())
  {    
    $this->dateWidget = new sfWidgetFormJQueryDate($dateOptions, $dateAttributes);
    $this->timeWidget = new sfWidgetFormJQueryTime($timeOptions, $timeAttributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js', 'last');
    $date = $this->dateWidget->render($name, $value, $attributes, $errors);
    $time = $this->timeWidget->render($name, $value, $attributes, $errors);
    
    return $date.$time;
  }
}
