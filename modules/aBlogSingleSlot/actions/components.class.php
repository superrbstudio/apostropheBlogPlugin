<?php
class aBlogSingleSlotComponents extends BaseaSlotComponents
{
  protected abstract $modelClass = 'aBlogPost';
  protected abstract $formClass = 'aBlogSlotSingleForm';
  
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
    if(isset($this->values['blog_post']))
      $this->aBlogPost = Doctrine::getTable($modelClass)->findOneBy('id', $this->values['blog_post']);
  }
}
