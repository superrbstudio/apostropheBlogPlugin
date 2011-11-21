<?php
/**
 */
class PluginaBlogItemTable extends Doctrine_Table
{
  /**
   * One of $post and $id must not be null
   */
  public static function getDisqusIdentifier($post, $id = null)
  {
    if ($post)
    {
      if (class_exists('apostropheImportersPluginConfiguration') && (strlen($post['disqus_thread_identifier'])))
      {
        return $post['disqus_thread_identifier'];
      }
      $id = $post['id'];
    }
    return sfConfig::get('app_a_disqus_identifierPrefix', '') . $id;
  }

  public static function getInstance()
  {
    return Doctrine_Core::getTable('aBlogItem');
  }
  
  public function filterByYMD($year=null, $month=null, $day=null, $q=null)
  {
    if(!$year && !$month && !$day)
      return $q;
    
    $rootAlias = $q->getRootAlias();
    
    $sYear = isset($year)? $year : 0;
    $sMonth = isset($month)? $month : 0;
    $sDay = isset($day)? $day : 0;
    $startDate = "$sYear-$sMonth-$sDay 00:00:00";
    
    $eYear = isset($year)? $year : 3000;
    $eMonth = isset($month)? $month : 12;
    $eDay = isset($day)? $day : 31;
    $endDate = "$eYear-$eMonth-$eDay 23:59:59";
    
    $q->addWhere($rootAlias.'.published_at BETWEEN ? AND ?', array($startDate, $endDate));
  }
  
  public function filterByCategory($category_id, Doctrine_Query $q)
  {
    $q->addWhere('c.slug = ?', $category_id);
  }
  
  public function filterByTag($tag, Doctrine_Query $q)
  {
    PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $tag, $q, array('nb_common_tag' => 1));
  }

  public function filterByEditable(Doctrine_Query $q, $user_id = null)
  {
    if(is_null($user_id))
    {
      $user_id = sfContext::getInstance()->getUser()->getGuardUser()->getId();
      if(sfContext::getInstance()->getUser()->hasCredential('admin'))
      {
        return;
      }
    }
    // Avoid a performance hit by fetching the ids we're allowed to edit and building
    // a whereIn clause rather than permanently joining to lots and lots of information.
    // This also avoids problems that occur when we discard information about categories
    // we don't control that were added by other people 
    
    // Thanks to Lasse Munk
    
    $item_ids = $this->createQuery('b')
      ->select('b.id')
      ->leftJoin('b.Editors e')
      ->leftJoin('b.Categories c')
      ->leftJoin('c.Users u')
      ->leftJoin('c.Groups g')
      ->leftJoin('g.Users gu')
      ->andWhere('author_id = ? OR e.id = ? OR u.id = ? OR gu.id = ?', array($user_id, $user_id, $user_id, $user_id))
      ->groupBy('b.id')
      ->execute(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
    // Careful: an empty WHERE IN clause matches everything
    if (!count($item_ids))
    {
      $q->andWhere('false <> false');
    }
    else
    {
      $q->andWhereIn('id', $item_ids);
    }
  }

  public function addPublished(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    $q->addWhere($rootAlias.'.status = ? AND '. $rootAlias.'.published_at <= NOW()', 'published');
  }
  
  public function addCategoriesForUser(sfGuardUser $user, $admin = false)
  {
    $q = $this->addCategories();  
    return Doctrine::getTable('aCategory')->addCategoriesForUser($user, $admin, $q);
  }

  public function addCategories(Doctrine_Query $q=null)
  {
    if(is_null($q))
      $q = Doctrine::getTable('aCategory')->createQuery();
      
    $q->addOrderBy('aCategory.name');
    return $q;
  }
  
  public function filterByCategories($categories, $q)
  {
    $categoryIds = array();
    foreach($categories as $blogCategory)
    {
      $categoryIds[] = $blogCategory['id'];
    }
    if(count($categoryIds))
    {
      $q->whereIn('c.id', $categoryIds);
    }
    return $q;
  }

  /**
   * Given an array of blogItems this function will populate its virtual page
   * areas with the current slot versions.
   * @param aBlogItem $blogItems
   */
  public static function populatePages($blogItems)
  {    
    $pageIds = array();
    foreach($blogItems as $aBlogItem)
    {
      $pageIds[] = $aBlogItem['page_id'];
    }
    $pages = array();
    if(count($pageIds))
    {
      $q = aPageTable::queryWithSlots();
      $q->whereIn('id', $pageIds);
      $fast = sfConfig::get('app_a_fasthydrate', false);
      $pagesInfo = $q->execute(array(), $fast ? Doctrine::HYDRATE_ARRAY : Doctrine::HYDRATE_RECORD);
      foreach($pagesInfo as $pageInfo)
      {
        $pages[] = aTools::cacheVirtualPage($pageInfo);
      }
    }
    $pagesById = aArray::listToHashById($pages);
    foreach ($blogItems as $aBlogItem)
    {
      if (isset($pagesById[$aBlogItem->page_id]))
      {
        $aBlogItem->Page = $pagesById[$aBlogItem->page_id];
      }
    }
  }

  public static function cachePages($blogItems)
  {
    foreach($blogItems as $blogItem)
    {
      aTools::cacheVirtualPages($blogItem->Page);
    }
  }

  public static function findOne($params)
  {
    return self::getInstance()->findOneBy('id', $params['id']);
  }

  public function findOneEditable($id, $user_id)
  {
    $q = $this->createQuery()
      ->addWhere('id = ?', $id);
    $this->filterByEditable($q, $user_id);
    return $q->fetchOne();
  }

  // Search for a substring in all event or blog titles. Slug prefix can be
  // @a_event_search_redirect or @a_blog_search_redirect
  
  static public function titleSearch($search, $slugPrefix)
  {
    $q = aPageTable::queryWithTitles();
    $q->addWhere('p.slug LIKE ?', array("$slugPrefix%"));
    $q->addWhere('s.value LIKE ?', array('%'.$search.'%'));
    $q->addWhere('p.archived IS FALSE');
    $virtualPages = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    $ids = array();
    foreach ($virtualPages as $page)
    {
      if (preg_match("/^$slugPrefix\?id=(\d+)$/", $page['slug'], $matches))
      {
        $ids[] = $matches[1];
      }
    }
    if (!count($ids))
    {
      return array();
    }
    else
    {
      return Doctrine::getTable('aBlogItem')->createQuery('e')->whereIn('e.id', $ids)->execute(array(), Doctrine::HYDRATE_ARRAY);
    }
  }
  
  // WARNING! DOES NOT escape categoryIds, which should be prevalidated thanks to aBlogSlotForm or similar
  // TODO: I had to make this blog specific because it is tied to assumptions about what the relation is called
  // and what makes an eligible item (published etc). Think about how to regeneralize this so we can use it with
  // media etc. (but right now we don't do such a thing)
  
  public function getTagsForCategories($categoryIds, $model, $popular = false, $limit = null)
  {
    if(!is_array($categoryIds))
    {
      $categoryIds = array($categoryIds);
    }
    
    $connection = Doctrine_Manager::connection();
    $pdo = $connection->getDbh();

    $innerQuery = "SELECT b.id AS id FROM a_blog_item b
                   LEFT JOIN a_blog_item_to_category bic ON b.id = bic.blog_item_id
                   LEFT JOIN a_category bc ON bic.category_id = bc.id
                   WHERE  b.status = 'published' AND b.published_at < NOW()";

    if(count($categoryIds))
    {
      $innerQuery.=" AND bc.id IN (".implode(',', $categoryIds).") ";
    }

    $innerQuery.= " GROUP BY b.id ";

    $query = "SELECT tg.tag_id, t.name, COUNT(tg.id) AS t_count FROM (
              $innerQuery
              ) as b
              LEFT JOIN tagging tg ON tg.taggable_id = b.id
              LEFT JOIN tag t ON t.id = tg.tag_id
              WHERE tg.taggable_model = '$model'";

    $query.= "GROUP BY tg.tag_id ";

    if($popular)
    {
      $query.="ORDER BY t_count DESC ";
    }
    else
    {
      $query.="ORDER BY t.name ASC ";
    }
    if(!is_null($limit))
    {
      $query.="LIMIT $limit";
    }

    $rs = $pdo->query($query);

    $tags = array();

    foreach($rs as $tag)
    {
      $name = $tag['name'];
      $tags[$name] = $tag['t_count'];
    }

    return $tags;
  }
  
  // Don't guess at what the current credentials regime is, call this
  static public function userCanPost($user = false)
  {
    if ($user === false)
    {
      $user = sfContext::getInstance()->getUser();
    }
    return ($user->hasCredential('blog_author') || $user->hasCredential('blog_admin'));
  }

  public function createQueryWithAll($alias = '')
  {
    $query = $this->createQuery($alias);

    $query->leftJoin($query->getRootAlias().'.Author au')
      ->leftJoin($query->getRootAlias().'.Categories c')
      ->leftJoin($query->getRootAlias().'.Page p');

    $query = $this->queryWithPages($query);

    return $query;
  }

  public function queryWithPages($query = null)
  {
    if(is_null($query))
    {
      $query = $this->createQuery();
    }
    $query->leftJoin($query->getRootAlias().'.Page p');
    $query = aPageTable::queryWithSlots(false, null, $query);
    

    return $query;
  }
  
  /**
   * Get everyone who is a potential blog author. Refactored here for easy overriding
   */
  public function queryForAuthors()
  {
    // The candidates to edit pages are candidates to author blogs
    $candidateGroup = sfConfig::get('app_a_edit_candidate_group', false);
    $sufficientGroup = sfConfig::get('app_a_edit_sufficient_group', false);
    
    $q = Doctrine::getTable('sfGuardUser')->createQuery('u');
    // Blog author candidates include blog editor candidates, plus admins - if they aren't listed here they
    // wind up losing authorship to the first person in the dropdown due to the validator!
    if ($candidateGroup && $sufficientGroup)
    {
      $q->leftJoin('u.Permissions p WITH p.name = "cms_admin"')->leftJoin('u.Groups g')->leftJoin('g.Permissions gp WITH gp.name = "cms_admin"')->addWhere('(g.name IN (?, ?)) OR (u.is_super_admin IS TRUE) OR gp.name = "cms_admin" OR p.name = "cms_admin"', array($candidateGroup, $sufficientGroup));
    }
    else
    {
      $q->leftJoin('u.Permissions p WITH p.name = "cms_admin"')->leftJoin('u.Groups g')->leftJoin('g.Permissions gp WITH gp.name = "cms_admin"')->addWhere('(u.is_super_admin IS TRUE) OR gp.name = "cms_admin" OR p.name = "cms_admin"');
    }
    return $q;
  }
}
