<?php

/**
 * Base actions for the apostropheBlogPlugin aBlog module.
 * 
 * @package     apostropheBlogPlugin
 * @subpackage  aBlog
 * @author      Your name here
 * @version     SVN: $Id: BaseComponents.class.php 12628 2008-11-04 14:43:36Z Kris.Wallsmith $
 */
abstract class BaseaBlogComponents extends sfComponents
{
  public function executeRecentPosts()
  {
  
    if (!isset($this->limit))
    {
      $this->limit = 5;
    }
    $q = Doctrine::getTable('aBlogPost')
      ->createQuery('p')
      ->addWhere('p.published = ?', true)
      ->orderBy('p.published_at desc')
      ->limit($this->limit);
      
    // Restrict to categories, by slug
    if (isset($this->categories))
    {
      $q->innerJoin('p.Category c')->andWhereIn('c.slug', $this->categories);
    } elseif (isset($this->notCategories))
    // Exclude categories, by slug
    {
      $q->leftJoin('p.Category c');
      foreach ($this->notCategories as $slug)
      {
        $q->andWhere('(c.slug <> ? OR c.slug IS NULL)', array($slug));
      }
    }
    
    $this->a_blog_posts = $q->execute();
  }

  public function executeTagSidebar($request)
  {
    if ($this->getRequestParameter('tag'))
    {
      $this->tag = TagTable::findOrCreateByTagname($this->getRequestParameter('tag'));
    }
    
    $this->popular = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true, 'limit' => 10));

    $this->tags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost'));
    
    $this->categories =  aTools::getCurrentPage()->BlogCategories;
    $aPageCategories = aTools::getCurrentPage()->aBlogPageCategory;
    if(count($aPageCategories) == 0)
    {
      $this->categories = Doctrine::getTable('aBlogCategory')
        ->createQuery('c')
        ->orderBy('c.name')
        ->execute();
    }
  }
}
