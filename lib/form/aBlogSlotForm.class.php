<?php    
class aBlogSlotForm extends BaseForm
{
  protected $id;
  public function __construct($id, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
    parent::__construct($defaults, $options, $CSRFSecret);
  }
  public function configure()
  {
    // ADD YOUR FIELDS HERE
    
    $this->widgetSchema['count'] = new sfWidgetFormInput(array(), array('size' => 2));
    $this->validatorSchema['count'] = new sfValidatorNumber(array('min' => 1, 'max' => 10));
		$this->widgetSchema->setHelp('count', '<span class="a-help-arrow"></span> Set the number of posts to display – 10 max.');
    if(!$this->hasDefault('count'))
		{
      $this->setDefault('count', 1);
    }

    $choices = array('title' => 'By Title', 'tags' => 'By Category And Tag');
    $this->setWidget('title_or_tag', new sfWidgetFormChoice(array('choices' => $choices)));
    $this->setValidator('title_or_tag', new sfValidatorChoice(array('choices' => array_keys($choices))));
    
    // We'll progressively enhance this with autocomplete so no data is needed here
    $this->setWidget('blog_posts', new sfWidgetFormChoice(array('multiple' => true, 'expanded' => false, 'choices' => array())));
    // TODO: really should be specific to posts, requires adding a custom query
    $this->validatorSchema['blog_posts'] = new sfValidatorDoctrineChoice(array('model' => 'aBlogItem', 'multiple' => true, 'required' => false));
    
    $this->widgetSchema['categories_list'] =
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory'));
    $this->validatorSchema['categories_list'] =
      new sfValidatorDoctrineChoice(array('model' => 'aCategory', 'multiple' => true, 'required' => false));
		$this->widgetSchema->setHelp('categories_list', '<span class="a-help-arrow"></span> Filter Posts by Category');
        
    $this->widgetSchema['tags_list']       = new sfWidgetFormInput(array(), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags_list']    = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setHelp('tags_list','<span class="a-help-arrow"></span> Filter Posts by Tag');
		        
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
