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
class aImportBlogTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('events', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of events', null),
      new sfCommandOption('posts', null, sfCommandOption::PARAMETER_REQUIRED, 'XML of posts', null)
      // add your own options here
    ));

    $this->namespace = 'apostrophe';
    $this->name = 'import-blog';
    $this->briefDescription = 'Imports a blog from an XML file';
    $this->detailedDescription = <<<EOF
Usage:

php symfony apostrophe:import-blog

See the Wiki for documentation of the XML format required.
EOF;
  }

  protected function execute($args = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getDoctrineConnection();

    if (!$this->askConfirmation("Importing any content will erase any existing content, are you sure? [y/N]", 'QUESTION_LARGE', false))
    {
      die("Import CANCELLED.  No changes made.\n");
    }
    $rootDir = $this->configuration->getRootDir();
    $dataDir = $rootDir . '/data/a';
    $options['events'] = $dataDir . '/events.xml';
    $options['posts'] = $dataDir . '/posts.xml';



    $importer = new aBlogImporter($connection, $options);
    $importer->import('posts');
    $importer->import('events');
  }

}
