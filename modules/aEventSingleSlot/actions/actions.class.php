<?php
require_once dirname(__FILE__).'/../../aBlogSingleSlot/actions/actions.class.php';
class aEventSingleSlotActions extends aBlogSingleSlotActions
{
  protected $modelClass = 'aEvent';
  protected $formClass = 'aEventSingleSlotForm';
}
  