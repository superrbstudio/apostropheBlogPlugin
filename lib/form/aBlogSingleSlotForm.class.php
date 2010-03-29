<?php    
class aBlogSingleSlotForm extends sfForm
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
    
    // A simple example: a slot with a single 'text' field with a maximum length of 100 characters
    $this->widgetSchema['search'] = new sfWidgetFormInput(array(), array('autocomplete' => 'off'));
    $this->validatorSchema['search'] = new sfValidatorString();
    
    $this->widgetSchema['blog_post'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['blog_post'] = new sfValidatorDoctrineChoice(array('model' => 'aBlogPost', 'multiple' => false));
    
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slotform-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
