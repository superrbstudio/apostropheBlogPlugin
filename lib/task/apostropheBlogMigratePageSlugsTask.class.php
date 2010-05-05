<?php

class apostropheBlogMigratePageSlugsTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('force', false, sfCommandOption::PARAMETER_NONE, 'No prompts'),
      // add your own options here
    ));

    $this->namespace        = 'apostropheBlog';
    $this->name             = 'migratePageSlugs';
    $this->briefDescription = 'Changes slugs used for virtual pages following update.';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();

    $blogItems = Doctrine::getTable('aBlogItem')->createQuery()
      ->execute();
    foreach($blogItems as $blogItem)
    {
      $blogItem->Page['slug'] = $blogItem->engine.'/'.$blogItem['id'];
      $blogItem->save();
    }
    echo("Slugs successfully migrated.\n");

  }

}

?>
