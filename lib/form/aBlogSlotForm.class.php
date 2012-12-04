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
    $this->validatorSchema['count'] = new sfValidatorNumber(array('min' => $min = sfConfig::get('app_aBlog_slot_minimum_posts', 1), 'max' => $max = sfConfig::get('app_aBlog_slot_maximum_posts', 20)));
    $this->widgetSchema->setHelp('count', '<span class="a-help-arrow"></span> Set the number of posts to display (between ' . $min . ' and ' . $max . ')');
    if(!$this->hasDefault('count'))
		{
      $this->setDefault('count', 3);
    }

    $choices = array('title' => 'By Title', 'tags' => 'By Category And Tag');
    $this->setWidget('title_or_tag', new sfWidgetFormChoice(array('choices' => $choices)));
    if (!$this->hasDefault('title_or_tag'))
    {
      $this->setDefault('title_or_tag', 'tags');
    }
    $this->setValidator('title_or_tag', new sfValidatorChoice(array('choices' => array_keys($choices))));
    
    aBlogToolkit::addBlogItemsWidget($this, 'aBlogPost', 'blog_posts');

    $this->widgetSchema['categories_list'] =
      new sfWidgetFormDoctrineChoice(array('multiple' => true, 'model' => 'aCategory'));
    $this->validatorSchema['categories_list'] =
      new sfValidatorDoctrineChoice(array('model' => 'aCategory', 'multiple' => true, 'required' => false));
		$this->widgetSchema->setHelp('categories_list', '<span class="a-help-arrow"></span> Filter Posts by Category');
   	$this->getWidget('categories_list')->setOption('query', Doctrine::getTable('aCategory')->createQuery()->orderBy('aCategory.name asc'));
        
    $this->widgetSchema['tags_list']       = new sfWidgetFormInput(array(), array('class' => 'tag-input', 'autocomplete' => 'off'));
    $this->validatorSchema['tags_list']    = new sfValidatorString(array('required' => false));
		$this->widgetSchema->setHelp('tags_list','<span class="a-help-arrow"></span> Filter Posts by Tag');
		        
    // Ensures unique IDs throughout the page
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }

  /**
   * Support method for aBlogToolkit::addBlogItemsWidget
   */
  public function getBlogItemIds($model, $name)
  {
    return $this->getDefault('blog_posts');
  }
}
