<?php

class aBlogImporter extends aImporter
{
  protected $authorMap;
  protected $defaultAuthorId;
  protected $tagToEntity = false;

  public function initialize($params)
  {
    // Default is to purge, for bc, but there should at least be a way to not do that ):
    $clear = isset($params['clear']) ? $params['clear'] : true;
    if (isset($params['posts']))
    {
      $this->posts = simplexml_load_file($params['posts']);
      if ($clear)
      {
        $this->sql->query('DELETE FROM a_blog_item WHERE TYPE = "post"');
      }
    }
    if (isset($params['events']))
    {
      $this->events = simplexml_load_file($params['events']);
      if ($clear)
      {
        $this->sql->query('DELETE FROM a_blog_item WHERE TYPE = "event"');
      }
    }
    if (isset($params['authors']))
    {
      $authorMapXml = simplexml_load_file($params['authors']);
      foreach ($authorMapXml->mapping as $mapping)
      {
        $this->authorMap[(string) $mapping['from']] = (string) $mapping['to'];
      }
    }
    if (isset($params['defaultUsername']))
    {
      $defaultUsername = $params['defaultUsername'];
    }
    else
    {
      $defaultUsername = 'admin';
    }
    $this->tagToEntity = $params['tag-to-entity'];
    $this->defaultAuthorId = $this->sql->queryOneScalar('SELECT id FROM sf_guard_user WHERE username=:username', array('username' => $defaultUsername));
    $this->imagesDir = $params['imagesDir'];
  }

  public function import($type = 'posts')
  {
    if($type == 'posts') {
      $singular = 'post';
    }
    else
    {
      $singular = 'event';
    }
    foreach($this->$type->$singular as $post)
    {
      if($type == 'posts') {
        $this->insertPost($post, 'post');
      } else {
        $this->insertPost($post, 'event');
      }
      $blog_id = $this->sql->lastInsertId();
      $categories = $post->categories;
      $categoryIds = array();
      foreach($categories->category as $category)
      {
        $name = $category->__toString();
        $categoryIds[] = $this->addCategory($name, $blog_id, $type);
      }

      $tagIds = array();
      if ($post->tags)
      {
        $tags = $post->tags;
        foreach($tags->tag as $tag)
        {
          $name = $tag->__toString();
          if (!$this->convertTagToEntity($name, $blog_id))
          {
            $tagIds[] = $this->addTag($name, $blog_id, $type);
          }
        }
      }
      
      if($type == 'posts') {
       $slug = '@a_blog_search_redirect?id='.$blog_id;
      } else {
        $slug = '@a_event_search_redirect?id='.$blog_id;
      }
      
      $post->Page->addAttribute('slug', aString::limitCharacters($slug, 255));
      $post->Page->addAttribute('title', aString::limitCharacters($post->title, 255));

      // In 1.5 virtual pages associated with engines should have 'engine' set to the appropriate engine.
      // Also published_at must be set for both the page and the post

      $page = $this->parsePage($post->Page, null, array('engine' => ($type === 'posts') ? 'aBlog' : 'aEvent', 'published_at' => $this->parseDateTime($post['published_at'])));
      $this->sql->query("UPDATE a_blog_item SET page_id=:page_id where id=:id", array('page_id' => $page['id'], 'id' => $blog_id));

      // Sync tags and categories to the associated page, enabling search
      foreach ($categoryIds as $categoryId)
      {
        $this->sql->query("INSERT INTO a_page_to_category (page_id, category_id) VALUES(:page_id, :category_id) ON DUPLICATE KEY UPDATE page_id = page_id", array('page_id' => $page['id'], 'category_id' => $categoryId));
      }
      
      foreach ($tagIds as $tagId)
      {
        $this->sql->query("INSERT INTO tagging (tag_id, taggable_model, taggable_id) VALUES(:tag_id, 'aPage', :taggable_id)", array('tag_id' => $tagId, 'taggable_id' => $page['id']));
      }
    }
  }
  
  // Parse alternative datestamp formats generously, 
  // get them MySQL ready 
  public function parseDateTime($when)
  {
    return date('Y-m-d H:i', strtotime($when));
  }

  public function addCategory($name, $blog_id, $type = 'posts')
  {
    $category = current($this->sql->query("SELECT * FROM a_category where name = :name", array('name' => $name)));
    if($category)
    {
      $category_id = $category['id'];
    }
    else
    {
      $s = "INSERT INTO a_category (name, created_at, updated_at, slug) ";
      $s.= "VALUES (:name, :created_at, :updated_at, :slug)";
      $params = array(
        'name' => $name,
        'created_at' => aDate::mysql(),
        'updated_at' => aDate::mysql(),
        'slug' => aTools::slugify($name)
      );
      $this->sql->query($s, $params);
      $category_id = $this->sql->lastInsertId();
    }
    $s = 'INSERT INTO a_blog_item_to_category (blog_item_id, category_id) VALUES(:blog_item_id, :category_id) ON DUPLICATE KEY UPDATE blog_item_id=blog_item_id';
    $parms = array(
        'blog_item_id' => $blog_id,
        'category_id' => $category_id
      );
    $this->sql->query($s, $parms);
    return $category_id;
  }
  
  public function convertTagToEntity($name, $blog_id)
  {
    if (!$this->tagToEntity)
    {
      return false;
    }
    $entity = current($this->sql->query("SELECT * FROM a_entity where name = :name", array('name' => $name)));
    if (!$entity)
    {
      return false;
    }
    $s = 'INSERT INTO a_entity_to_blog_item (entity_id, blog_item_id) VALUES (:entity_id, :blog_item_id) ON DUPLICATE KEY UPDATE entity_id = entity_id';
    $params = array('entity_id' => $entity['id'], 'blog_item_id' => $blog_id);
    $this->sql->query($s, $params);
  }

  public function addTag($name, $blog_id, $type = 'posts')
  {
    $tag = current($this->sql->query("SELECT * FROM tag where name = :name", array('name' => $name)));
    if($tag)
    {
      $tag_id = $tag['id'];
    }
    else
    {
      $s = "INSERT INTO tag (name) ";
      $s.= "VALUES(:name)";
      $params = array(
        'name' => str_replace('/', '-', $name));
      $this->sql->query($s, $params);
      $tag_id = $this->sql->lastInsertId();
    }
    $s = 'INSERT INTO tagging (tag_id, taggable_model, taggable_id) VALUES (:tag_id, :taggable_model, :taggable_id) ON DUPLICATE KEY UPDATE taggable_id = taggable_id';
    $params = array(
        'tag_id' => $tag_id,
        'taggable_model' => ($type === 'posts') ? 'aBlogPost' : 'aEvent',
        'taggable_id' => $blog_id
      );
    $this->sql->query($s, $params);
    return $tag_id;
  }

  /**
   * Now handles both posts and events. This is much better than
   * trying to maintain two code forks. As per usual, events
   * get neglected that way.
   */
  public function insertPost($post, $type)
  {
    $slug = $this->slugify(isset($post['slug']) ? $post['slug'] : $post->title);
    // Don't crash on overlong slugs in recent PDO/MySQL
    $slug = aString::limitCharacters($slug, 255);
    // Posts belong to the defaultUsername if a valid username is not matched.
    // Check the author map if there is one
    $author_id = $this->defaultAuthorId;
    if (isset($post->author))
    {
      if (isset($this->authorMap[(string) $post->author]))
      {
        $real_author_id = $this->sql->queryOneScalar('select id from sf_guard_user where username = :username', array('username' => (string) $this->authorMap[(string) $post->author]));
      }
      else
      {
        $real_author_id = $this->sql->queryOneScalar('select id from sf_guard_user where username = :username', array('username' => (string) $post->author));
      }
    }
    if (isset($real_author_id) && strlen($real_author_id))
    {
      $author_id = $real_author_id;
    }
   
    $engine = ($type === 'post') ? 'aBlog' : 'aEvent';
    $class = ($type === 'post') ? 'aBlogPost' : 'aEvent';
    $table = Doctrine::getTable($class);
    $template = $table->getDefaultTemplate();
    if (!$template) 
    {
      echo("Can't determine default template, did you disable all blog or event templates in app.yml?\n");
      exit(1);
    }

    $created_at = isset($post['created_at']) ? $post['created_at'] : $post['published_at'];
    $updated_at = isset($post['updated_at']) ? $post['updated_at'] : $post['published_at'];
    $params = array(
      // Recent PDO/MySQL will flunk overlong fields, don't crash
      "title" => aString::limitCharacters((string) $post->title, 255),
      "author_id" => $author_id,
      "slug_saved" => true,
      "status" => 'published',
      "allow_comments" => false,
      "template" => $template,
      "published_at" => $this->parseDateTime($post['published_at']),
      "created_at" => $this->parseDateTime($created_at),
      "updated_at" => $this->parseDateTime($updated_at),
      "type" => $type,
      "slug" => $slug
    );

    if (sfConfig::get('app_aBlog_allow_comments_individually'))
    {
      $params['allow_comments'] = sfConfig::get('app_aBlog_allow_comments_initially') ? 1 : 0;
    }
    // On custom sites blog posts may also have locations
    if (isset($post->location) && strlen($post->location))
    {
      $params['location'] = $post->location;
    }
    if ($type === 'event')
    {
      $params = array_merge($params, array(
        "start_date" => date('Y-m-d', strtotime($post['start_date'])),
        "end_date" => date('Y-m-d', strtotime($post['end_date'])),
      ));
      if (isset($post['all_day']) && (((string) $post['all_day']) === 'true'))
      {
        $params = array_merge($params, array('start_time' => null, 'end_time' => null));
      }
      else
      {
        // Not an all day event, consider time of day
        $params = array_merge($params, array(
          "end_time" => date('H:i', strtotime($post['end_date'])),
          "start_time" => date('H:i', strtotime($post['start_date'])),
        ));
      }
    }
    if (isset($post['disqus_thread_identifier']))
    {
      if (!class_exists('apostropheImportersPluginConfiguration'))
      {
        echo("WARNING: apostropheImportersPlugin not installed, cannot migrate disqus threads\n");
      }
      else
      {
        $params['disqus_thread_identifier'] = $post['disqus_thread_identifier'];
      }
    }
    $this->sql->insert('a_blog_item', $params);
    $blog_id = $this->sql->lastInsertId();
  }
  
  /**
   * Generate a unique, safe slug
   */
  public function slugify($slug)
  {
    $slug = aTools::slugify($slug);
    while (count($this->sql->query('select id from a_blog_item where slug = :slug', array('slug' => $slug))))
    {
      if (preg_match('/^(.*)-(\d+)$/', $slug, $matches))
      {
        $rest = $matches[1];
        $ordinal = $matches[2];
        $ordinal++;
        $slug = $rest . "-" . $ordinal;
      }
      else
      {
        $slug .= "-1";
      }
    }
    return $slug;
  }
}
