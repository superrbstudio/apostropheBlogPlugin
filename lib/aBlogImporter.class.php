<?php

class aBlogImporter extends aImporter
{
  protected $authorMap;
  protected $defaultAuthorId;

  public function initialize($params)
  {
    $this->sql->query('DELETE FROM a_blog_item');
    if (isset($params['posts']))
    {
      $this->posts = simplexml_load_file($params['posts']);
    }
    if (isset($params['events']))
    {
      $this->events = simplexml_load_file($params['events']);
    }
    if (isset($params['authors']))
    {
      $authorMapXml = simplexml_load_file($params['authors']);
      foreach ($authorMapXml->mapping as $mapping)
      {
        $this->authorMap[(string) $mapping['from']] = (string) $mapping['to'];
      }
    }
    $this->defaultAuthorId = $this->sql->queryOneScalar('SELECT id FROM sf_guard_user WHERE username="admin"');
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
        $this->insertPost($post);
      } else {
        $this->insertEvent($post);
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
          $tagIds[] = $this->addTag($name, $blog_id, $type);
        }
      }
      
      if($type == 'posts') {
       $slug = '@a_blog_search_redirect?id='.$blog_id;
      } else {
        $slug = '@a_event_search_redirect?id='.$blog_id;
      }
      
      $post->Page->addAttribute('slug', $slug);
      $post->Page->addAttribute('title', $post->title);

      // In 1.5 virtual pages associated with engines should have 'engine' set to the appropriate engine.
      // Also published_at must match
      $page = $this->parsePage($post->Page, null, array('engine' => ($type === 'posts') ? 'aBlog' : 'aEvent', 'published_at' => (string) $post['published_at']));
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

  public function insertPost($post)
  {
    $slug = $this->slugify(isset($post['slug']) ? $post['slug'] : $post->title);

    // Posts belong to the admin if a valid username is not provided.
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
        $real_author_id = $this->sql->queryOneScalar('select id from sf_guard_user where username = :username', array('username' => (string) $post->username));
      }
    }
    if (isset($real_author_id) && strlen($real_author_id))
    {
      $author_id = $real_author_id;
    }
    
    $params = array(
      "title" => $post->title,
      "author_id" => $author_id,
      "slug_saved" => true,
      "status" => 'published',
      "allow_comments" => false,
      "template" => "singleColumnTemplate",
      "published_at" => $post['published_at'],
      "type" => "post",
      "slug" => $slug
    );
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

  public function insertEvent($event)
  {
    $slug = $this->slugify(isset($event['slug']) ? $event['slug'] : $event->title);
    $s = "INSERT INTO a_blog_item (title, author_id, slug_saved, status, allow_comments, template, published_at, start_date, start_time, end_date, end_time, type, slug )";
    $s.= "VALUES (:title, :author_id, :slug_saved, :status, :allow_comments, :template, :published_at, :start_date, :start_time, :end_date, :end_time, :location, :type, :slug)";
    $params = array(
      "title" => $event->title,
      "author_id" => $this->author_id,
      "slug_saved" => true,
      "status" => 'published',
      "allow_comments" => false,
      "template" => "singleColumnTemplate",
      "published_at" => $event['published_at'],
      "location" => $event->location,
      "start_date" => date('Y-m-d', strtotime($event['start_date'])),
      "start_time" => date('h:i', strtotime($event['start_date'])),
      "end_date" => date('Y-m-d', strtotime($event['end_date'])),
      "end_time" => date('h:i', strtotime($event['end_date'])),
      "type" => "event",
      "slug" => $slug
    );
    $this->sql->query($s, $params);
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