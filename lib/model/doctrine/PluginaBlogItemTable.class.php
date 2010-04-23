<?php
/**
 */
class PluginaBlogItemTable extends Doctrine_Table
{
  protected $categoryColumn = 'posts';
  
  public function filterByYMD(Doctrine_Query $q, sfWebRequest $request)
  {
    $rootAlias = $q->getRootAlias();
    
    $sYear = $request->getParameter('year', 0);
    $sMonth = $request->getParameter('month', 0);
    $sDay = $request->getParameter('day', 0);
    $startDate = "$sYear-$sMonth-$sDay 00:00:00";
    
    $eYear = $request->getParameter('year', 3000);
    $eMonth = $request->getParameter('month', 12);
    $eDay = $request->getParameter('day', 31);
    $endDate = "$eYear-$eMonth-$eDay 23:59:59";
    
    $q->addWhere($rootAlias.'.published_at BETWEEN ? AND ?', array($startDate, $endDate));
  }
  
  public function filterByCategory(Doctrine_Query $q, sfWebRequest $request)
  {
    $rootAlias = $q->getRootAlias();
    $q->addWhere('c.name = ?', $request->getParameter('cat'));
  }
  
  public function filterByTag(Doctrine_Query $q, sfWebRequest $request)
  {
    PluginTagTable::getObjectTaggedWithQuery($q->getRootAlias(), $request->getParameter('tag'), $q, array('nb_common_tag' => 1));
  }

  public function filterByEditable(Doctrine_Query $q, $user_id)
  {
    $rootAlias = $q->getRootAlias();
    $q->leftJoin($rootAlias.'.Categories c');
    $q->leftJoin('c.Users u');
    $q->leftJoin($rootAlias.'.Editors e');
    $q->addWhere('author_id = ? OR u.id = ? OR e.id = ?', array($user_id, $user_id, $user_id));
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
      $q->execute();
    }
  }
}