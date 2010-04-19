<?php
/**
 */
class PluginaBlogPostTable extends aBlogItemTable
{
  protected $categoryColumn = 'posts';

  static function findOne($params)
  {
    return Doctrine::getTable('aBlogPost')->createQuery()->
      where('id', $params['id'])->
      execute();
  }

}