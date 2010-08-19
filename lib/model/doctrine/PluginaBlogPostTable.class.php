<?php
/**
 */
class PluginaBlogPostTable extends aBlogItemTable
{
  protected $categoryColumn = 'posts';
  private static $engineCategoryCache;
  
  public static function getInstance()
  {
    return Doctrine_Core::getTable('aBlogPost');
  }

  public function getEngineCategories()
  {
    if(!isset(self::$engineCategoryCache))
    {
      $engines = Doctrine::getTable('aPage')->createQuery()
        ->leftJoin('aPage.BlogCategories Categories')
        ->addWhere('engine = ?', 'aBlog')
        ->addWhere('admin != ?', true)
        ->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

      $engineCache = array();
      foreach($engines as $engine)
      {
        $engineCache[$engine['slug']] = array();
        foreach($engine['BlogCategories'] as $category)
          $engineCache[$engine['slug']][] = $category['name'];
      }
      self::$engineCategoryCache = $engineCache;
    }
    
    return self::$engineCategoryCache;
  }

}