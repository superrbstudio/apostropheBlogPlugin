<?php

/*
 *
 * This file is part of Apostrophe
 * (c) 2009 P'unk Avenue LLC, www.punkave.com
 */

/**
 * @package    apostrophePlugin
 * @subpackage Tasks
 * @author     Dan Ordille <dan@punkave.com>
 */
class aBlogImportTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application', 'frontend'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('events', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of events', null),
      new sfCommandOption('posts', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of posts', null),
      new sfCommandOption('authors', null, sfCommandOption::PARAMETER_REQUIRED, 'XML author username mapping', null)
      // add your own options here
    ));

    $this->namespace = 'apostrophe';
    $this->name = 'import-blog';
    $this->briefDescription = 'Imports a blog from an XML file';
    $this->detailedDescription = <<<EOF
Usage:

php symfony apostrophe:import-blog

Separate files should be specified with the --events and --posts options.
Most actual blogs only have posts.

If your xml file specifies a disqus_thread_identifier attribute for a post
element, Apostrophe will point to that existing disqus thread rather than
creating a new thread identifier for the post. To use that feature you 
must install the apostropheImportersPlugin in your project and run
apostrophe:migrate to add the disqus_thread_identifier column. This is
only worth the trouble if you have existing disqus threads for your
imported blog posts.

You can specify an authors file which maps old usernames to new usernames.
The task first looks for a match in the authors file (if any), then for a match
among the usernames on the new site, and as a last resort assigns the
article's authorship to the "admin" user.

See trac.apostrophe.org for documentation of the XML format required.
EOF;
  }

  protected function execute($args = array(), $options = array())
  {
    aTaskTools::signinAsTaskUser($this->configuration, $options['connection']);
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getDoctrineConnection();

    if (is_null($options['posts']) && is_null($options['events']))
    {
      die("You must specify at least one of the posts and events options with a path to the xml file.\n");
    }
    if (!$this->askConfirmation("Importing the same content twice will result in duplicate content, are you sure? [y/N]", 'QUESTION_LARGE', false))
    {
      die("Import CANCELLED.  No changes made.\n");
    }
    $rootDir = $this->configuration->getRootDir();

    $importer = new aBlogImporter($connection, $options);
    if (!is_null($options['events']))
    {
      $importer->import('events');
    }
    if (!is_null($options['posts']))
    {
      $importer->import('posts');
    }
  }

}
