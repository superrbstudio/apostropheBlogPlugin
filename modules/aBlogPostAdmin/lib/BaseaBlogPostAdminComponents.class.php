<?php

require_once dirname(__FILE__).'/aBlogPostAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogPostAdminGeneratorHelper.class.php';

/**
 * aBlogPostAdmin actions.
 *
 * @package    apostropheBlogPlugin
 * @subpackage aBlogPostAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class BaseaBlogPostAdminComponents extends sfComponents
{
  public function executeTagList(sfRequest $request)
  {
    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true, 'limit' => 10));

    if (!$this->a_blog_post = Doctrine::getTable('aBlogPost')->find($request->getParameter('id')))
    {
      $this->a_blog_post = new aBlogPost();
    }
  }
}
