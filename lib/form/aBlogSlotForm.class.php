<?php    
class aBlogSlotForm extends sfForm
{
  // Ensures unique IDs throughout the page
  protected $id;
  public function __construct($id, $defaults)
  {
    $this->id = $id;
    parent::__construct();
    $this->setDefaults($defaults);
  }
  public function configure()
  {
    // ADD YOUR FIELDS HERE
    
    $this->widgetSchema['count'] = new sfWidgetFormInput(array(), array('size' => 2));
    $this->validatorSchema['count'] = new sfValidatorNumber(array('min' => 1, 'max' => 10));
		$this->widgetSchema->setHelp('count', __('Set the number of posts to display – 10 max.', array(), 'apostrophe_blog'));
		
		$this->setDefault('count', 1);
    
    $this->widgetSchema['categories_list'] =
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogCategory'));
    $this->validatorSchema['categories_list'] =
      new sfValidatorDoctrineChoice(array('model' => 'aBlogCategory', 'required' => false));
		$this->widgetSchema->setHelp('categories_list', __('Filter posts by Category', array(), 'apostrophe_blog'));
    
    $this->widgetSchema['tags_list']       = new sfWidgetFormInput(array(), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags_list']    = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setHelp('tags_list', __('Filter posts by Tag', array(), 'apostrophe_blog'));
		        
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
