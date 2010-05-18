<?php
class aEventSlotComponents extends BaseaSlotComponents
{
  protected $modelClass = 'aEvent';
  protected $formClass = 'aEventSlotForm';
  
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new $this->formClass($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c');
    Doctrine::getTable($this->modelClass)->addPublished($q);
    if(isset($this->values['categories_list']) && count($this->values['categories_list']) > 0)  
      $q->andWhereIn('c.id', $this->values['categories_list']);
    if(isset($this->values['tags_list']) && strlen($this->values['tags_list']) > 0)
      PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $this->values['tags_list'], $q, array('nb_common_tags' => 1));

    $limit = isset($this->values['count']) ? $this->values['count'] : 5;
   
    if(!isset($this->options['slideshowOptions']))
		{ // If no slideshow options are set, use the defaults
	    $this->options['slideshowOptions'] = array('width' => 100, 'height' => 100, 'resizeType' => 'c');
		}
		else
		{ // If -some- slideshow options are set, make sure to include defaults where not specified
	    $this->options['slideshowOptions'] = array(
				'width' => ((isset($this->options['slideshowOptions']['width']))? $this->options['slideshowOptions']['width']:100), 
				'height' => ((isset($this->options['slideshowOptions']['height']))? $this->options['slideshowOptions']['height']:100), 
				'resizeType' => ((isset($this->options['slideshowOptions']['resizeType']))? $this->options['slideshowOptions']['resizeType']:'c'), 
			);
		}

		// If we are not specifying a template to use at the area/singleton slot level, then use the default templates
    if(!isset($this->options['template']))
		{
			$this->options['template'] =	$this->aBlogPost['template']; 
		}

    $this->options['excerptLength'] = $this->getOption('excerptLength', 200);
    $this->options['maxImages'] = $this->getOption('maxImages', 1);

   	aEventTable::getInstance()->addUpcomming($q, $limit);
    
    $this->aEvents = $q->execute();  

    aBlogItemTable::populatePages($this->aEvents);

  }
}
