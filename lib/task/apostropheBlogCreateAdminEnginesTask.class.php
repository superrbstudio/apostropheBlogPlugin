<?php

class apostropheBlogCreateAdminEnginesTask extends sfBaseTask
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
    $this->name             = 'createAdminEngines';
    $this->briefDescription = 'Changes slugs used for virtual pages following update.';
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();


    $admin = Doctrine::getTable('aPage')->findOneBy('slug', '/admin');
    
    //Check if admin blog engine page already exists
    $blogAdminEngine = Doctrine::getTable('aPage')->createQuery()
      ->addWhere('engine = ?', 'aBlog')
      ->addWhere('admin = ?', true)
      ->fetchOne();
    echo $blogAdminEngine;
    if(!$blogAdminEngine)
    {
      $blogAdminEngine = new aPage();
      $blogAdminEngine['engine'] = 'aBlog';
      $blogAdminEngine['slug'] = '/admin/aBlog';
      $blogAdminEngine['template'] = 'default';
      $blogAdminEngine['admin'] = true;
      $blogAdminEngine->getNode()->insertAsLastChildOf($admin);
    }
    else
    {
      echo "Blog admin engine page already exists.\n";
    }

    $eventAdminEngine = Doctrine::getTable('aPage')->createQuery()
      ->addWhere('engine = ?', 'aEvent')
      ->addWhere('admin = ?', true)
      ->fetchOne();

    if(!$eventAdminEngine)
    {
      $eventAdminEngine = new aPage();
      $eventAdminEngine['engine'] = 'aEvent';
      $eventAdminEngine['slug'] = '/admin/aEvent';
      $eventAdminEngine['template'] = 'default';
      $eventAdminEngine['admin'] = true;
      $eventAdminEngine->getNode()->insertAsLastChildOf($admin);
    }
    else
    {
      echo "Blog admin engine page already exists.\n";
    }

  }

}

?>
