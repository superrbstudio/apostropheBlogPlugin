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
        ->execute();

      $engineCache = array();
      foreach($engines as $engine)
      {
        $engineCache[$engine->slug] = array('engine' => $engine);
        $engineCache[$engine->slug]['categories'] = array();
        foreach($engine->BlogCategories as $category)
          $engineCache[$engine->slug]['categories'][] = $category->name;
      }
      self::$engineCategoryCache = $engineCache;
    }
    
    return self::$engineCategoryCache;
  }

}