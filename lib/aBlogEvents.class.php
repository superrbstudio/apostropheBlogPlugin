<?php

class aBlogEvents
{
  // command.post_command
  static public function listenToCommandPostCommandEvent(sfEvent $event)
  {
    $task = $event->getSubject();
    
    if ($task->getFullName() === 'apostrophe:migrate')
    {
      self::migrate();
    }
  }
  
  static public function migrate()
  {
    $migrate = new aMigrate(Doctrine_Manager::connection()->getDbh());
    $blogIsNew = false;
    echo("Migrating apostropheBlogPlugin...\n");
    
    if (!$migrate->tableExists('a_blog_item'))
    {
      $migrate->sql(array(
"        CREATE TABLE a_blog_editor (blog_item_id INT, user_id INT, PRIMARY KEY(blog_item_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
"CREATE TABLE a_blog_item (id INT AUTO_INCREMENT, author_id INT, page_id INT, title VARCHAR(255) NOT NULL, slug_saved TINYINT(1) DEFAULT '0', excerpt TEXT, status VARCHAR(255) DEFAULT 'draft' NOT NULL, allow_comments TINYINT(1) DEFAULT '0' NOT NULL, template VARCHAR(255) DEFAULT 'singleColumnTemplate', published_at DATETIME, type VARCHAR(255), start_date DATE, start_time TIME, end_date DATE, end_time TIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slug VARCHAR(255), INDEX a_blog_item_type_idx (type), UNIQUE INDEX a_blog_item_sluggable_idx (slug), INDEX author_id_idx (author_id), INDEX page_id_idx (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
"        ALTER TABLE a_blog_editor ADD CONSTRAINT a_blog_editor_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id);",
"        ALTER TABLE a_blog_editor ADD CONSTRAINT a_blog_editor_blog_item_id_a_blog_item_id FOREIGN KEY (blog_item_id) REFERENCES a_blog_item(id);",
"        ALTER TABLE a_blog_item ADD CONSTRAINT a_blog_item_page_id_a_page_id FOREIGN KEY (page_id) REFERENCES a_page(id) ON DELETE CASCADE;",
"        ALTER TABLE a_blog_item ADD CONSTRAINT a_blog_item_author_id_sf_guard_user_id FOREIGN KEY (author_id) REFERENCES sf_guard_user(id) ON DELETE SET NULL;",
      ));
    }
    
    if (!$migrate->tableExists('a_blog_item_to_category'))
    {
      $migrate->sql(array(
        "CREATE TABLE a_blog_item_to_category (blog_item_id INT, category_id INT, PRIMARY KEY(blog_item_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;",
        "ALTER TABLE a_blog_item_to_category ADD CONSTRAINT a_blog_item_to_category_category_id_a_category_id FOREIGN KEY (category_id) REFERENCES a_category(id) ON DELETE CASCADE;",
        "ALTER TABLE a_blog_item_to_category ADD CONSTRAINT a_blog_item_to_category_blog_item_id_a_blog_item_id FOREIGN KEY (blog_item_id) REFERENCES a_blog_item(id) ON DELETE CASCADE;"
        ));
      $oldCategories = $migrate->query('SELECT * FROM a_blog_category');
      $newCategories = $migrate->query('SELECT * FROM a_category');
      $nc = array();
      foreach ($newCategories as $newCategory)
      {
        $nc[$newCategory['slug']] = $newCategory;
      }
      $oldIdToNewId = array();
      foreach ($oldCategories as $category)
      {
        if (isset($nc[$category['slug']]))
        {
          $migrate->query('UPDATE a_category SET posts = :posts, events = :events WHERE slug = :slug', $category);
          $oldIdtoNewId[$category['id']] = $nc[$category['slug']]['id'];
        }
        else
        {
          $migrate->query('INSERT INTO a_category (name, description, slug, posts, events) VALUES (:name, :description, :slug, :posts, :events)', $category);
          $oldIdToNewId[$category['id']] = $migrate->lastInsertId();
        }
      }
      $oldMappings = $migrate->query('SELECT * FROM a_blog_item_category');
      foreach ($oldMappings as $info)
      {
        $info['category_id'] = $oldIdToNewId[$info['blog_category_id']];
        $migrate->query('INSERT INTO a_blog_item_to_category (blog_item_id, category_id) VALUES (:blog_item_id, :category_id)', $info);
      }
    }
    
    if (!$migrate->getCommandsRun())
    {
      echo("Your database is already up to date.\n\n");
    }
    else
    {
      echo($migrate->getCommandsRun() . " SQL commands were run.\n\n");
    }
    echo("Done!\n");
    
  }
}

