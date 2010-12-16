<?php

class PluginaEventEngineForm extends aPageForm
{
  public function setup()
  {
    parent::setup();

    $this->useFields();
    $q = Doctrine::getTable('aEvent')->addCategories();
    $this->setWidget('categories_list', new sfWidgetFormDoctrineChoice(array('multiple' => true, 'query' => $q, 'model' => 'aCategory')));
    $this->widgetSchema->setLabel('categories_list', 'Events Categories');
    $this->widgetSchema->setHelp('categories_list','(Defaults to All Cateogories)');
    $this->setValidator('categories_list', new sfValidatorDoctrineChoice(array('multiple' => true, 'query' => $q, 'model' => 'aCategory', 'required' => false)));
    $this->widgetSchema->setNameFormat('enginesettings[%s]');
    $this->widgetSchema->setFormFormatterName('aPageSettings');
  }
}