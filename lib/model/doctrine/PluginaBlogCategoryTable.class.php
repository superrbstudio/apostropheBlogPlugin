<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginaBlogCategoryTable extends Doctrine_Table
{
public function getTagsForCategories($categoryIds, $model, $popular = false, $limit = null)
  {
    if(!is_array($categoryIds))
    {
      $categoryIds = array($categoryIds);
    }

    $connection = Doctrine_Manager::connection();
    $pdo = $connection->getDbh();

    $query = "SELECT tg.tag_id, t.name, COUNT(tg.id) AS t_count FROM a_blog_item b
      LEFT JOIN a_blog_category bc ON b.category_id = bc.id
      LEFT JOIN tagging tg ON tg.taggable_id = b.id
      LEFT JOIN tag t ON t.id = tg.tag_id
      WHERE tg.taggable_model = '$model'
      AND b.published IS TRUE";

    if(count($categoryIds))
    {
      $query.=" AND b.category_id IN (".implode(',', $categoryIds).") ";
    }

    $query.= " GROUP BY tg.tag_id ";

    if($popular)
    {
      $query.=" ORDER BY t_count DESC ";
    }
    else
    {
      $query.=" ORDER BY t.name ASC ";
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

}
