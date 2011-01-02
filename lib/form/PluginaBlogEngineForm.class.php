<?php

class PluginaBlogEngineForm extends aPageForm
{
  public $model = 'aBlogPost';
  public function setup()
  {
    parent::setup();

    $this->useFields(array('categories_list'));
    $q = Doctrine::getTable($this->model)->addCategories();
    $this->widgetSchema->setLabel('categories_list', 'Content Categories');
    $this->widgetSchema->setHelp('categories_list','(Defaults to All Cateogories)');
    $this->getValidator('categories_list')->setOption('required', false);
    $this->widgetSchema->setNameFormat('enginesettings[%s]');
		// We use the aPageSettings formatter here instead of aAdmin because it puts H4 tags around the labels for styling
    $this->widgetSchema->setFormFormatterName('aPageSettings');
  }
}