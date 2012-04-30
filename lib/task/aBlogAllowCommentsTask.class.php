<?php

/*
 *
 * This file is part of Apostrophe
 * (c) 2009 P'unk Avenue LLC, www.punkave.com
 */

/**
 * @package    apostrophePlugin
 * @subpackage Tasks
 * @author     Tom Boutell <tom@punkave.com>
 */
class aBlogAllowCommentsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application', 'frontend'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The database connection', 'doctrine'),
      new sfCommandOption('yes', null, sfCommandOption::PARAMETER_NONE, 'Permit comments', null),
      new sfCommandOption('no', null, sfCommandOption::PARAMETER_NONE, 'Forbid comments', null),
      // add your own options here
    ));

    $this->namespace = 'apostrophe';
    $this->name = 'allow-comments';
    $this->briefDescription = 'Allows or disallows comments on existing posts';
    $this->detailedDescription = <<<EOF
Usage:

php symfony apostrophe:allow-comments [--yes|--no]

This task is used to check or uncheck the "allow comments" box for 
all existing posts.

apostrophe:allow-comments --yes is equivalent to checking the box for
every post. 

apostrophe:allow-comments --no is equivalent to unchecking the box for
every post.

Note that if comments are not enabled, or the 
app_aBlog_allow_comments_individually flag is not set, this command
serves no purpose.

This task is usually used immediately after setting 
app_aBlog_allow_comments_individually and 
app_aBlog_allow_comments_initially.
EOF;
  }

  protected function execute($args = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    if ((!$options['yes']) && (!$options['no']))
    {
      die("You must specify either --yes or --no.\n");
    }
    $sql = new aMysql();
    if ($options['yes'])
    {
      $sql->query('UPDATE a_blog_item SET allow_comments = TRUE');
    }
    else
    {
      $sql->query('UPDATE a_blog_item SET allow_comments = FALSE');
    }
  }
}
