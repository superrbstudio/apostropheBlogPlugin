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

  protected $engine = 'aEvent';
  protected $categoryColumn = 'events';

  public function setup()
  {
    parent::setup();

    $this->setWidget('start_date', new sfWidgetFormJQueryDate(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		);

    $this->setValidator('start_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $this->setWidget('start_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $this->setValidator('start_time', new sfValidatorTime(array('required' => false, 'time_output' => 'g:iA')));

    $this->setWidget('end_date', new sfWidgetFormJQueryDate(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
		);

    $this->setValidator('end_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $this->setWidget('end_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $this->setValidator('end_time', new sfValidatorTime(array('required' => false, 'time_output' => 'g:iA')));

    $this->getWidgetSchema()->setDefault('start_date', date('Y/m/d'));
    $this->getWidgetSchema()->setDefault('end_date', date('Y/m/d'));

		$this->setWidget('all_day', new sfWidgetFormInputCheckbox(array('label' => 'All Day')));
		$this->setValidator('all_day', new sfValidatorBoolean());
		

		if ((strtotime("today", strtotime($this->object->start_date)) == strtotime($this->object->end_time)) && (strtotime("+1 day", strtotime($this->object->start_date)) == strtotime($this->object->end_date)))
		{
			$this->getWidgetSchema()->setDefault('all_day', true);
		}

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }
}
