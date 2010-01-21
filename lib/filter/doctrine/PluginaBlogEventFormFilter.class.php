<?php

/**
 * PluginaBlogEvent form.
 *
 * @package    filters
 * @subpackage aBlogEvent *
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class PluginaBlogEventFormFilter extends BaseaBlogEventFormFilter
{
	public function configure()
	{
		unset(
      $this['type'],					// We are already filtering by Events or Posts - This is redundant
      $this['created_at'],		//
      $this['updated_at'],		//
      $this['published_at'],	//
      $this['slug'],					// Filter by slug does what?
      $this['version'],				// 
      $this['media'] 					// Removed media filtering. If introduced later is should be a toggle "Has Media / Doesn't Has"
    );

    $this->widgetSchema['title']        = new sfWidgetFormFilterInput(array('with_empty' => false));
    $this->widgetSchema['excerpt']      = new sfWidgetFormFilterInput(array('with_empty' => false));
    $this->widgetSchema['body']         = new sfWidgetFormFilterInput(array('with_empty' => false));

    // $this->widgetSchema['start_date'] = new sfWidgetFormJQueryDate();
    // $this->widgetSchema['end_date'] = new sfWidgetFormJQueryDate();		
	}
}