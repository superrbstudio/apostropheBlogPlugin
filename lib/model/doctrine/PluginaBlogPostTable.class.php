<?php
/**
 */
class PluginaBlogPostTable extends aBlogItemTable
{
  protected $categoryColumn = 'posts';

  public static function findOne($params)
  {
    return Doctrine::getTable('aBlogPost')->findOneBy('id', $params['id']);
  }

}