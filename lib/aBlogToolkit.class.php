<?php

class aBlogToolkit {

  public static function isFilterSet($filter, $name)
  {
    $value = aBlogToolkit::getFilterFieldValue($filter, $name);
    if($value === null || $value == '')
    {
      return false;
    }
    if(is_array($name) && count($name) > 1)
    {
      return false;
    }
   
    return true;
  }
  
  public static function getFilterFieldValue($filter, $name)
  {
    $field = $filter[$name];
    $value = $field->getValue();
    $types = $filter->getFields();
    $type = $types[$field->getName()];
    switch($type){
      case 'Enum':
        return $value;
      case 'Boolean':
        return aBlogToolkit::getValueForId($field, $value);
      case 'ForeignKey':
      case 'ManyKey':
        if(is_array($value))
        {
          $values = array();
          foreach($value as $v) $values[] = aBlogToolkit::getValueForId($field, $v);
        }
        else
        {
          $values = aBlogToolkit::getValueForId($field, $value);
        }
        return $values;
      case 'Text':
      case 'Number':
        return $value['text'];  
    }
    
  }
  
  public static function getValueForId($field, $id)
  {
    if(is_null($id)) return null;
    $choices = $field->getWidget()->getChoices();
    return $choices[$id];
  }  
 
  // If we use aTools::slugify directly it gets confused by additional
  // parameters passed to the slug builder by the behavior
  public static function slugify($s, $item)
  {
    return aTools::slugify($s);
  }
  
  // Used by blog admin, event admin, blog engine and event engine
  
  // If $categories is not null it should be an array of category objects; search results
  // must match at least one of the categories. However if there are NO categories in the array,
  // all results are accepted
  
  // Pass a 'term' argument rather than a 'q' argument for a nice jquery.autocomplete friendly AJAX array
  // with 'label' and 'value'
  static public function searchBody($action, $slugMatch, $modelClass, $categories, sfWebRequest $request)
  {
    $now = date('YmdHis');
    
    // create the array of pages matching the query
    
    $ajax = false;
    if ($request->hasParameter('term'))
    {
      $ajax = true;
      $q = $request->getParameter('term');
      // Wildcarding is better for autocomplete
      $q .= '*';
    }
    else
    {
      $q = $request->getParameter('q');
      if ($request->hasParameter('x'))
      {
        // We sometimes like to use input type="image" for presentation reasons, but it generates
        // ugly x and y parameters with click coordinates. Get rid of those and come back.
        return $action->redirect(sfContext::getInstance()->getController()->genUrl($request->getParameter('module') . '/' . $request->getParameter('action'), true) . '?' . http_build_query(array("q" => $q)));
      }
    }
    
    $key = strtolower(trim($q));
    $key = preg_replace('/\s+/', ' ', $key);
    $replacements = sfConfig::get('app_a_search_refinements', array());
    if (isset($replacements[$key]))
    {
      $q = $replacements[$key];
    }

    try
    {
      $q = "($q) AND slug:$slugMatch";
      $values = aZendSearch::searchLuceneWithValues(Doctrine::getTable('aPage'), $q, aTools::getUserCulture());
    } catch (Exception $e)
    {
      // Lucene search error. TODO: display it nicely if they are always safe things to display. For now: just don't crash
      $values = array();
    }
    $nvalues = array();

    // The truth is that Zend cannot do all of our filtering for us, especially
    // permissions-based. So we can do some other filtering as well, although it
    // would be bad not to have Zend take care of the really big cuts (if 99% are
    // not being prefiltered by Zend, and we have a Zend max results of 1000, then 
    // we are reduced to working with a maximum of 10 real results).

    foreach ($values as $value)
    {
      if ($ajax && (count($nvalues) >= sfConfig::get('app_aBlog_autocomplete_max', 10)))
      {
        break;
      }
      
      // 1.5: the names under which we store columns in Zend Lucene have changed to
      // avoid conflict with also indexing them
      $info = unserialize($value->info_stored);

      if ((!is_null($categories)) && (count($categories)))
      {
        // Filtering categories this way is not ideal, we could drown in 1000 results for
        // an unrelated category and not get a chance to winnow out the handful for this
        // category. Think about a way to get Lucene to do it. However that is tricky 
        // because we do a lot of gnarly things like merging categories
        if (count($categories))
        {
          $good = false;
          $categories = aArray::listToHashById($categories);
          $ids = preg_split('/,/', $value->category_ids);
          foreach ($ids as $id)
          {
            if (isset($categories[$id]))
            {
              $good = true;
            }
          }
          if (!$good)
          {
            continue;
          }
        }
      }

      if ($value->published_at > $now)
      {
        continue;
      }
      if (!aPageTable::checkPrivilege('view', $info))
      {
        continue;
      }
      $nvalue = $value;
      $nvalue->page_id = $info['id'];
      $nvalue->slug = $nvalue->slug_stored;
      $nvalue->title = $nvalue->title_stored;
      $nvalue->summary = $nvalue->summary_stored;
      // Virtual page slug is a named Symfony route, it wants search results to go there
      $nvalue->url = $action->getController()->genUrl($nvalue->slug, true);
      $nvalue->class = $modelClass;
      $nvalues[] = $nvalue;
    }
    $values = $nvalues;
    if ($ajax)
    {
      // We need the IDs of the blog posts, not their virtual pages
      $pageIds = array();
      foreach ($values as $value)
      {
        $pageIds[] = $value->page_id;
      }
      $action->results = array();
      if (count($pageIds))
      {
        $infos = Doctrine::getTable($modelClass)->createQuery('p')->select('p.id, p.page_id, p.status')->whereIn('p.page_id', $pageIds)->fetchArray();
        // At this point, if we're an admin, we have some posts on our list that are not actually
        // useful to return as AJAX results because we only care about posts that are published when
        // we're building up a posts slot
        foreach ($infos as $info)
        {
          if ($info['status'] !== 'published')
          {
            continue;
          }
          $pageIdToPostId[$info['page_id']] = $info['id'];
        }
        foreach ($values as $value)
        {
          if (isset($pageIdToPostId[$value->page_id]))
          {
            // Titles are stored as entity-escaped HTML text, so decode to meet
            // the expectations of autocomplete
            $action->results[] = array('label' => html_entity_decode($value->title, ENT_COMPAT, 'UTF-8'), 'value' => $pageIdToPostId[$value->page_id]);
          }
        }
      }
      return 'Autocomplete';
    }
    $action->pager = new aArrayPager(null, sfConfig::get('app_a_search_results_per_page', 10));    
    $action->pager->setResultArray($values);
    $action->pager->setPage($request->getParameter('page', 1));
    $action->pager->init();
    $action->pagerUrl = "aBlog/search?" . http_build_query(array("q" => $q));
    // setTitle takes care of escaping things
    $action->getResponse()->setTitle(aTools::getOptionI18n('title_prefix') . 'Search for ' . $q . aTools::getOptionI18n('title_suffix'));
    $action->results = $action->pager->getResults();
  }
}