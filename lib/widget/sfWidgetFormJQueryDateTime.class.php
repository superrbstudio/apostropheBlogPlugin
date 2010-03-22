<?php


class sfWidgetFormJQueryDateTime extends sfWidgetForm
{
  
  protected $dateWidget;
  protected $timeWidget;

    
  protected function configure($options = array(), $attributes = array())
  {    

    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');

    $this->dateWidget = new sfWidgetFormJQueryDate($options, $attributes);
    $this->timeWidget = new sfWidgetFormJQueryTime($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    sfContext::getInstance()->getResponse()->addJavascript('/sfJqueryReloadedPlugin/js/plugins/jquery.autocomplete.min.js', 'last');
    $date = $this->dateWidget->render($name, $value, $attributes, $errors);
    $time = $this->timeWidget->render($name, $value, $attributes, $errors);
    
    return $date.$time;
  }
}
