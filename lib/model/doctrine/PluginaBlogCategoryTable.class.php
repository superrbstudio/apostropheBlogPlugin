<?php
/**
 */
class PluginaBlogCategoryTable extends Doctrine_Table
{
  
  public function addCategoriesForUser(sfGuardUser $user, $admin = false, Doctrine_Query $q = null)
  {
    $q = clone $q;
    if(is_null($q))
      $q = $this->createQuery();
    
    if(!$admin)
    {
      $q->innerJoin('aBlogCategory.Users')
        ->andwhere('aBlogCategory.Users.id = ?', $user['id']);
    }
    return $q;
  }
  
}