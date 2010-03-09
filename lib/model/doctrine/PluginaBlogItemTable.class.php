<?php
/**
 */
class PluginaBlogItemTable extends Doctrine_Table
{  
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
}