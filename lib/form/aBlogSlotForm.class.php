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
    $this->validatorSchema['count'] = new sfValidatorNumber(array('min' => 0, 'max' => 10));
    
    $this->widgetSchema['categories_list'] =
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aBlogCategory'));
    $this->validatorSchema['categories_list'] =
      new sfValidatorDoctrineChoice(array('model' => 'aBlogCategory', 'required' => false));
    
    $this->widgetSchema['tags_list']       = new sfWidgetFormInput(array(), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags_list']    = new sfValidatorString(array('required' => false));
        
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
