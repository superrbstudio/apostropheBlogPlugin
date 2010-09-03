<?php
/**
 */
class PluginaBlogItemTable extends Doctrine_Table
{
  protected $categoryColumn = 'posts';

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
    $q->addWhere('c.name = ?', $category_id);
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

    $rootAlias = $q->getRootAlias();
    $q->leftJoin($rootAlias.'.Editors e');
    $q->leftJoin($rootAlias.'.Categories c');
    $q->leftJoin('c.Users u');
    $q->leftJoin('c.Groups g');
    $q->leftJoin('g.users gu');
    $q->andWhere('author_id = ? OR e.id = ? OR u.id = ? OR gu.id = ?', array($user_id, $user_id, $user_id, $user_id));
  }

  public function addPublished(Doctrine_Query $q)
  {
    $rootAlias = $q->getRootAlias();
    $q->addWhere($rootAlias.'.status = ? AND '. $rootAlias.'.published_at <= NOW()', 'published');
  }
  
  public function addCategoriesForUser(sfGuardUser $user, $admin = false)
  {
    $q = $this->addCategories();  
    return Doctrine::getTable('aBlogCategory')->addCategoriesForUser($user, $admin, $q);
  }

  public function addCategories(Doctrine_Query $q=null)
  {
    if(is_null($q))
      $q = Doctrine::getTable('aBlogCategory')->createQuery();
      
    $q->andwhere('aBlogCategory.'.$this->categoryColumn .'= ?', true);
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
    if(count($pageIds))
    {
      $q = aPageTable::queryWithSlots();
      $q->whereIn('id', $pageIds);
      $pages = $q->execute();
      aTools::cacheVirtualPages($pages);
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
}