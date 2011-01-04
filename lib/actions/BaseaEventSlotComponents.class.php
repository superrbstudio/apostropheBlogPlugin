<?php
abstract class BaseaEventSlotComponents extends BaseaBlogSlotComponents
{
  protected $modelClass = 'aEvent';
  protected $formClass = 'aEventSlotForm';
  
  public function getQuery()
  {
    $q = parent::getQuery();
    $q->orderBy('start_date asc, start_time asc');
    return $q;
  }
}
