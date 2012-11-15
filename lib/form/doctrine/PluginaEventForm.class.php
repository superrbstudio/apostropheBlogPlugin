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

  public function setup()
  {
    parent::setup();

    aEventForm::addDateAndTimeFields($this, 'publication');

    $start = strtotime(aDate::mysql($this->getObject()->start_date));
    $end = strtotime(aDate::mysql($this->getObject()->end_date));
    if (is_null($this->getObject()->start_time) && is_null($this->getObject()->end_time))
    {
      $this->getWidgetSchema()->setDefault('all_day', true);
    }

    $this->setWidget('location', new sfWidgetFormTextarea());
    $this->setValidator('location', new sfValidatorString(array('required' => false)));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
    
    $this->validatorSchema->setPostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateEndDate')))
    );
  }

  /**
   * Factored out for easy reuse as a mixin for other forms that
   * are similar
   */
  static public function addDateAndTimeFields($form, $after)
  {
    $form->setWidget('start_date', new aWidgetFormJQueryDate(
      array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
    );
    $form->widgetSchema->moveField('start_date', sfWidgetFormSchema::AFTER, $after);

    $form->setValidator('start_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $form->setWidget('start_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $form->setValidator('start_time', new sfValidatorTime(array('required' => false)));

    $form->widgetSchema->moveField('start_time', sfWidgetFormSchema::AFTER, 'start_date');

    $form->setWidget('end_date', new aWidgetFormJQueryDate(
      array('image' => '/apostrophePlugin/images/a-icon-datepicker.png'))
    );

    $form->setValidator('end_date', new sfValidatorDate(
      array(
        'required' => true,
      )));

    $form->widgetSchema->moveField('end_date', sfWidgetFormSchema::AFTER, 'start_time');

    $form->setWidget('end_time', new aWidgetFormJQueryTime(array(), array('twenty-four-hour' => false, 'minutes-increment' => 30)));
    $form->setValidator('end_time', new sfValidatorTime(array('required' => false)));

    $form->widgetSchema->moveField('end_time', sfWidgetFormSchema::AFTER, 'end_date');

    $form->getWidgetSchema()->setDefault('start_date', date('Y/m/d'));
    $form->getWidgetSchema()->setDefault('end_date', date('Y/m/d'));

    $form->setWidget('all_day', new sfWidgetFormInputCheckbox(array('label' => 'All Day')));
    $form->setValidator('all_day', new sfValidatorBoolean());

    $form->widgetSchema->moveField('all_day', sfWidgetFormSchema::AFTER, 'end_time');
  }
  
  public function validateEndDate($validator, $values)
  {
    $start = $values['start_date'] . ' ' . $values['start_time'];
    $end = $values['end_date'] . ' ' . $values['end_time'];
    if ($end < $start)
    {
      // Technically the problem might be the date but we show them on one row
      // anyway so always attach the error to the time which is easier to style
      $error = new sfValidatorError($validator, 'Ends before it begins!');
      throw new sfValidatorErrorSchema($validator, array('end_date' => $error));
    }
    return $values;
  }
  
  
  public function updateObject($values = null)
  {
    if (is_null($values))
    {
      $values = $this->getValues();
    }
    
    if ($values['all_day'])
    {
      $values['start_time'] = null;
      $values['end_time'] = null;
    }
    return parent::updateObject($values);
  }
}
