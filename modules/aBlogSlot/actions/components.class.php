<?php
class aBlogSlotComponents extends BaseaSlotComponents
{
  protected $modelClass = 'aBlogPost';
  
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aBlogSlotForm($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $q = Doctrine::getTable($this->modelClass)->createQuery()
      ->leftJoin($this->modelClass.'.Author a')
      ->leftJoin($this->modelClass.'.Categories c');
    if(isset($this->values['categories_list']) && count($this->values['categories_list']) > 0)  
      $q->andWhereIn('c.id', $this->values['categories_list']);
    if(isset($this->values['tags_list']) && strlen($this->values['tags_list']) > 0)
      PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $this->values['tags_list'], $q, array('nb_common_tag' => 1));
    if(isset($this->values['count']))
      $q->limit($this->values['count']);
    $this->aBlogPosts = $q->execute();
    
      
  }
}
