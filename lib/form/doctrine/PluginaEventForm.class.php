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

    $this->setWidget('start_date', new sfWidgetFormJQueryDateTime(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')
		));

    $this->setValidator('start_date', new sfValidatorDateTime());

    $this->setWidget('end_date', new sfWidgetFormJQueryDateTime(
			array('image' => '/apostrophePlugin/images/a-icon-datepicker.png')
		));

    $this->setValidator('end_date', new sfValidatorDateTime());

    $this->getWidgetSchema()->setDefault('start_date', date('Y/m/d H:i'));
    $this->getWidgetSchema()->setDefault('end_date', date('Y/m/d H:i'));

    $this->widgetSchema->setNameFormat('a_blog_item[%s]');
  }

  public function updateCategoriesList($values)
  {
    $link = array();
    if(!is_array($values))
      $values = array();
    foreach ($values as $value)
    {
      $existing = Doctrine::getTable('aBlogCategory')->findOneBy('name', $value);
      if($existing)
      {
        $aBlogCategory = $existing;
      }
      else
      {
        $aBlogCategory = new aBlogCategory();
        $aBlogCategory['name'] = $value;
      }
      $aBlogCategory['events'] = true;
      $aBlogCategory->save();
      $link[] = $aBlogCategory['id'];
    }
    if(!is_array($this->values['categories_list']))
    {
      $this->values['categories_list'] = array();
    }
    $this->values['categories_list'] = array_merge($link, $this->values['categories_list']);
  }
}
