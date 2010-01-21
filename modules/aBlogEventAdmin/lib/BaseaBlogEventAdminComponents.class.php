<?php

require_once dirname(__FILE__).'/aBlogEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogEventAdminGeneratorHelper.class.php';

/**
 * aBlogEventAdmin actions.
 *
 * @package    apostropheBlogPlugin
 * @subpackage aBlogEventAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseaBlogEventAdminComponents extends sfComponents
{
  public function executeTagList(sfRequest $request)
  {
    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogEvent', 'sort_by_popularity' => true, 'limit' => 10));

    if (!$this->a_blog_event = Doctrine::getTable('aBlogEvent')->find($request->getParameter('id')))
    {
      $this->a_blog_event = new aBlogEvent();
    }
  }
}
