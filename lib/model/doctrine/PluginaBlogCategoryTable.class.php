<?php
/**
 */
class PluginaBlogCategoryTable extends Doctrine_Table
{

  public function addCategoriesForUser(sfGuardUser $user, $admin = false, Doctrine_Query $q = null)
  {
    if (is_null($q))
    {
      $q = $this->createQuery();
    }
    else
    {
      $q = clone $q;
    }
    if(!$admin)
    {
      // This will perform well if the user is the current user and Doctrine has
      // retained the relation to groups
      $groups = $user->getGroups();
      $groupIds = aArray::getIds($groups);
      $q->leftJoin('aBlogCategory.Groups')
        ->leftJoin('aBlogCategory.Users');
      // This is necessary because Doctrine doesn't have proper grouping syntax
      // available except via DQL
      $where = '';
      // Don't get burned by an empty IN clause matching everything
      if (count($groupIds))
      {
        $where .= 'aBlogCategory.Groups.id IN (' . implode(',', $groupIds) . ') OR ';
      }
      $q->andWhere('(' . $where . 'aBlogCategory.Users.id = ?)', $user['id']);
    }
    return $q;
  }
  
  public function getTagsForCategories($categoryIds, $model, $popular = false, $limit = null)
  {
    if(!is_array($categoryIds))
    {
      $categoryIds = array($categoryIds);
    }

    $connection = Doctrine_Manager::connection();
    $pdo = $connection->getDbh();

    $innerQuery = "SELECT b.id AS id FROM a_blog_item b
                   LEFT JOIN a_blog_item_category bic ON b.id = bic.blog_item_id
                   LEFT JOIN a_blog_category bc ON bic.blog_category_id = bc.id
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
  
  public static function getCategoriesForPage($page)
  {
	    return Doctrine::getTable('aBlogCategory')->createQuery('c')
	      ->innerJoin('c.Pages as p')
	      ->where('p.id = ?', $page['id'])
	      ->orderBy('c.name')
	      ->execute();
	}


}
