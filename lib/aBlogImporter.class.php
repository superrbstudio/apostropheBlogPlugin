<?php

class aBlogImporter extends aImporter
{
  protected $author_id;

  public function  initialize($params)
  {
    $this->sql->query('DELETE FROM a_blog_item');
    $this->posts = simplexml_load_file($params['posts']);
    $this->events = simplexml_load_file($params['events']);
    $author_row = current($this->sql->query('SELECT * FROM sf_guard_user WHERE username="admin"'));
    $this->author_id = $author_row['id'];
  }

  public function import($type = 'posts')
  {
    foreach($this->$type->post as $post)
    {
      if($type == 'posts') {
        $this->insertPost($post);
      } else {
        $this->insertEvent($post);
      }
      
      $blog_id = $this->sql->lastInsertId();
      $categories = $post->categories;
      foreach($categories->category as $category)
      {
        $name = $category->__toString();
        $this->addCategory($name, $blog_id, $type);
      }
      
      if($type == 'posts') {
       $slug = '@a_blog_search_redirect?id='.$blog_id;
      } else {
        $slug = '@a_event_search_redirect?id='.$blog_id;
      }
      
      $post->Page->addAttribute('slug', $slug);
      $post->Page->addAttribute('title', $post->title);

      $page = $this->parsePage($post->Page);

      $this->sql->query("UPDATE a_blog_item SET page_id=:page_id where id=:id", array('page_id' => $page['id'], 'id' => $blog_id));
    }
  }

  public function addCategory($name, $blog_id, $type = 'posts')
  {
    $category = current($this->sql->query("SELECT * FROM a_category where name = :name", array('name' => $name)));
    if($category)
    {
      if($type == 'posts')
      {
        $this->sql->query("UPDATE a_category SET posts=1 WHERE id=:id", array('id' => $category['id']));
      }
      elseif($type == 'events')
      {
        $this->sql->query("UPDATE a_category SET events=1 WHERE id=:id", array('id' => $category['id']));
      }
      $category_id = $category['id'];
    }
    else
    {
      $s = "INSERT INTO a_category (name, posts, events, created_at, updated_at, slug) ";
      $s.= "VALUES( :name, :posts, :events, :created_at, :updated_at, :slug)";
      $params = array(
        'name' => $name,
        'posts' => ($type == 'posts'),
        'events' => ($type == 'events'),
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
    try{
      $this->sql->query($s, $parms);
    } catch(Exception $e) {
      exit();
    }
    
  }

  public function insertPost($post)
  {
    $s = "INSERT INTO a_blog_item (title, author_id, slug_saved, status, allow_comments, template, published_at, type, slug )";
    $s.= "VALUES (:title, :author_id, :slug_saved, :status, :allow_comments, :template, :published_at, :type, :slug)";
    $params = array(
      "title" => $post->title,
      "author_id" => $this->author_id,
      "slug_saved" => true,
      "status" => 'published',
      "allow_comments" => false,
      "template" => "singleColumnTemplate",
      "published_at" => $post['published_at'],
      "type" => "post",
      "slug" => $post['slug']
    );
    $this->sql->query($s, $params);
  }

  public function insertEvent($event)
  {
    $s = "INSERT INTO a_blog_item (title, author_id, slug_saved, status, allow_comments, template, published_at, start_date, start_time, end_date, end_time, type, slug )";
    $s.= "VALUES (:title, author_id, :slug_saved, :status, :allow_comments, :template, :published_at, :start_date, :start_time, :end_date, :end_time, :type, :slug)";
    $params = array(
      "title" => $event->title,
      "author_id" => $this->author_id,
      "slug_saved" => true,
      "status" => 'published',
      "allow_comments" => false,
      "template" => "singleColumnTemplate",
      "published_at" => $event['published_at'],
      "start_date" => date('Y-m-d', strtotime($event['start_date'])),
      "start_time" => date('h:i', strtotime($event['start_date'])),
      "end_date" => date('Y-m-d', strtotime($event['end_date'])),
      "end_time" => date('h:i', strtotime($event['end_date'])),
      "type" => "event",
      "slug" => $event['slug']
    );
    $this->sql->query($s, $params);
  }

}