<?php

class aBlogEngineForm extends aPageForm
{
  public function configure()
  {
    $this->useFields();
    $choicesD = Doctrine::getTable('aBlogCategory')->findAll();
    $choices = array();
    foreach($choicesD as $choice)
    {
      $choices[$choice->getId()] = $choice->getName();
    }
    $choices['NULL'] = "Uncategorized";
    $this->setWidget('blog_categories_list', new sfWidgetFormChoice(array('multiple' => true, 'choices' => $choices)));    
    $this->widgetSchema->setLabel('blog_categories_list', 'blog Categories');
    $this->widgetSchema->setHelp('blog_categories_list','(Defaults to All Cateogories)');
    //$this->setValidator('blog_categories_list', new sfValidatorChoice(array('multiple' => true, 'choices' => $choices, 'required' => false)));
    $this->setValidator('blog_categories_list', new sfValidatorPass());
    $this->widgetSchema->setNameFormat('enginesettings[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  
  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['blog_categories_list']))
    {
      $this->setDefault('blog_categories_list', $this->getCategoriesDefaults());
    }
  }
  
  public function getCategoriesDefaults()
  {
    $defaults = $this->object->BlogCategories->getPrimaryKeys();
    foreach($this->object->aBlogPageCategory as $bc)
    {
      if($bc->getBlogCategoryId() === null)
      {
        $defaults[] = 'NULL';
      }
    }
    return $defaults;
  }
  
  public function saveBlogCategoriesList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['blog_categories_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $existing = $this->getCategoriesDefaults();
    $values = $this->getValue('blog_categories_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('BlogCategories', array_values($unlink));
      if(in_array('NULL', $unlink))
      {
        Doctrine::getTable('aBlogPageCategory')->createQuery('bc')
          ->delete()
          ->where('bc.page_id = ? AND bc.blog_category_id IS NULL', $this->object->getId())
          ->execute();
      }
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      if(in_array('NULL', $link))
      {
        $abpc = new aBlogPageCategory();
        $abpc->setPageId($this->object->getId());
        $abpc->setBlogCategoryId(null);
        $abpc->save();
      }
      $this->object->link('BlogCategories', array_values($link));
    }
  }
}
