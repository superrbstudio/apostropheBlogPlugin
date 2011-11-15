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
  
  // Formerly used in many places, this method is now used solely to implement
  // typeahead for blog post and event titles in blog and event slots. The categories
  // parameter is never used. In cases where a search service is installed we now
  // trust these assumptions and provide a simple implementation of AJAX typeahead.
  // In cases where the old Zend Lucene search implementation is still in effect we
  // default to the old implementation for maximum backwards compatibility.

  static public function searchBody($action, $slugMatch, $modelClass, $categories, sfWebRequest $request)
  {
    if (aTools::$searchService)
    {
      $q = $request->getParameter('term');
      // TODO: utf8 here when supported 
      $wildcard = false;
      if (function_exists('mb_strtolen'))
      {
        $wildcard = preg_match('/\p{L}+$/u', $q, $matches);
      }
      else
      {
        $wildcard = preg_match('/(\w+)$/', $q, $matches);
      }
      if ($wildcard)
      {
        // Wildcard at end of final word if it's not too short. If it's too short Zend reacts by returning nothing
        // for the entire query, ouch
        $q .= '*';
      }
        
      $query = Doctrine::getTable('aPage')->createQuery('p')->select('p.*, bi.*')->andWhere('p.slug LIKE ?', $slugMatch . '%')->innerJoin('p.BlogItem bi');
      aTools::$searchService->addSearchToQuery($query, $q);
      $matches = $query->fetchArray();
      $action->results = array();
      foreach ($matches as $match)
      {
        $info = aTools::$searchService->getInfoForResult($match);
        // Titles are stored as entity-escaped HTML text, so decode to meet
        // the expectations of autocomplete
        $action->results[] = array('label' => html_entity_decode($info['title_stored'], ENT_COMPAT, 'UTF-8'), 'value' => $match['BlogItem']['id']);
      }
      return 'Autocomplete';
    }
    
    $now = date('YmdHis');
    
    // create the array of pages matching the query
    
    $ajax = false;
    if ($request->hasParameter('term'))
    {
      $ajax = true;
      $q = $request->getParameter('term');
      // Wildcarding is great for autocomplete, but only if the last word is not too short.
      // If the last word is under 3 characters Zend will flunk the whole thing!
      if (preg_match('/(\w+)$/', $q, $matches) && strlen($matches[1]) > 2)
      {
        $q .= '*';
      }
    }
    else
    {
      $q = $request->getParameter('q');
      if ($request->hasParameter('x'))
      {
        // We sometimes like to use input type="image" for presentation reasons, but it generates
        // ugly x and y parameters with click coordinates. Get rid of those and come back. Keep
        // all the other stuff
        return $action->redirect(sfContext::getInstance()->getController()->genUrl(aUrl::addParams($request->getParameter('module') . '/' . $request->getParameter('action'), array('q' => $q, 'cat' => $request->getParameter('cat'), 'tag' => $request->getParameter('tag'), 'year' => $request->getParameter('year'), 'month' => $request->getParameter('month'), 'day' => $request->getParameter('day')))));
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

    if ($request->hasParameter('cat'))
    {
      $categories = Doctrine::getTable('aCategory')->createQuery()->where('slug = ?', array($request->getParameter('cat')))->execute(array(), Doctrine::HYDRATE_ARRAY);
    }
    if (is_null($categories))
    {
      $categoryIds = array();
    }
    else
    {
      $categoryIds = aArray::getIds($categories);
    }
    
    foreach ($values as $value)
    {
      
      if ($ajax && (count($nvalues) >= sfConfig::get('app_aBlog_autocomplete_max', 10)))
      {
        break;
      }
      
      // 1.5: the names under which we store columns in Zend Lucene have changed to
      // avoid conflict with also indexing them
      $info = unserialize($value->info_stored);

      // Do a whole bunch of filtering that can't be easily done at the Lucene level.
      // This is not ideal because the 1000 results we consider might not meet the
      // criteria and some later set of results might. For 2.0 we need to find something
      // that fully merges text search and other criteria without complaint

      // The main performance killer isn't MySQL, it's Doctrine object hydration. Just keep it light
      
      
      if (count($categoryIds) && (!count(Doctrine::getTable('aPage')->createQuery('p')->where('p.id = ?', $info['id'])->innerJoin('p.Categories c')->select('p.id, c.id')->andWhereIn('c.id', $categoryIds)->execute(array(), Doctrine::HYDRATE_NONE))))
      {
        continue;
      }

      if ($request->hasParameter('tag') && (!count(Doctrine::getTable('Tagging')->createQuery('ta')->innerJoin('ta.Tag t WITH t.name = ?', $request->getParameter('tag'))->where('ta.taggable_model = ? AND ta.taggable_id = ?', array('aPage', $info['id']))->execute(array(), Doctrine::HYDRATE_NONE))))
      {
        continue;
      }
      
      // Filter search results chronologically. How to do this depends on whether
      // we're dealing with blog or events
      
      $year = sprintf("%04d", $request->getParameter('year'));
      $month = sprintf("%02d", $request->getParameter('month'));
      $day = sprintf("%02d", $request->getParameter('day'));
      
      // This if is gross and ought to be refactored by calling a method on
      // the actions class which is differently implemented by the two classes
      if (get_class($action) === 'aEventActions')
      {
        if ($day > 0)
        {
          if (!aBlogToolkit::between("$year-$month-$day", $value->start_date, $value->end_date))
          {
            continue;
          }
        }
        elseif ($month > 0)
        {
          if (!aBlogToolkit::between("$year-$month", substr($value->start_date, 0, 7), substr($value->end_date, 0, 7)))
          {
            continue;
          }
        }
        elseif ($year > 0)
        {
          if (!aBlogToolkit::between("$year", substr($value->start_date, 0, 4), substr($value->end_date, 0, 4)))
          {
            continue;
          }
        }
      }
      else
      {
        // We store this one real picky in a keyword so it's searchable at the lucene level
        if (preg_match('/^(\d\d\d\d)(\d\d)(\d\d)/', $value->published_at, $matches))
        {
          list($dummy, $pyear, $pmonth, $pday) = $matches;
          if ($year > 0)
          {
            if ($pyear != $year)
            {
              continue;
            }
          }
          if ($month > 0)
          {
            if ($pmonth != $month)
            {
              continue;
            }
          }
          if ($day > 0)
          {
            if ($pday != $day)
            {
              continue;
            }
          }
        }
      }

      // Regardless of the above if it ain't published yet we can't see it
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
  
  static public function filterForEngine($options)
  {
    // This method filters the virtual pages, tags and categories associated with a particular engine based on 
    // specified criteria such as tag, category, publication date, etc. 
    
    // Strategy: do Lucene queries and direct SQL queries that will get us all the info about relevant categories, 
    // tags and virtual pages. Then turn that into a select distinct query for each of those things. The resulting
    // information is sufficient to populate the filters sidebar with options that are still relevant given the 
    // other criteria in effect, and also to fetch the result pages (you'll want to do that with a LIMIT and an IN 
    // query looking at the first n IDs returned by this method). 

    // The options array looks like this. Note that all of these are optional and if each is unspecified or empty
    // no restriction is made on that particular basis. 'categoryIds' is used to limit to the categories associated
    // with the engine page, while 'categorySlug' is used to limit to a category specified by the user as a 
    // filter. The 'q' option is Lucene search.
    
    // array(
    //   'q' => 'gromit',
    //   'author' => 'username',
    //   'categoryIds' => array(1, 3, 5), 
    //   'categorySlug' => 'cheese', 
    //   'tag' => 'wensleydale', 
    //   'slugStem' => '@a_event_search_redirect', 
    //   'year' => 2010, # Optional, if present only 2010 is shown
    //   'month' => 12, # Optional, if present only Dec. 2010 is shown
    //   'day' => 15, # Optional, if present only Dec. 15th 2010 is shown
    //   'byEventDateRange' => true, # For events only, joins with a_blog_item to get the range
    //   'byPublishedAt' => true, # For blog posts or pages

    // The returned value looks like this:
    
    // array(
    //   'categoriesInfo' => array('slug' => 'cheese', 'name' => 'Cheese'),
    //   'tagNames' => array('wensleydale'),
    //   'pageIds' => array(10, 15, 20, 25),
    //   'authors' => array('jsmith', 'bdoyle')

    $alphaSort = isset($options['alphaSort']) && $options['alphaSort'];
    
    if (isset($options['slugStem']))
    {
      $params['slug_pattern'] = $options['slugStem'] . '%';
    }
    
    if (isset($options['q']) && (strlen($options['q'])))
    {
      $q = $options['q'];
      $key = strtolower(trim($q));
      $key = preg_replace('/\s+/', ' ', $key);
      $replacements = sfConfig::get('app_a_search_refinements', array());
      if (isset($replacements[$key]))
      {
        $q = $replacements[$key];
      }
      // Prefilter the search results to blog posts only, deal with publication date and view privs.
      // This is a lot easier with search services
      if (aTools::$searchService)
      {
        $query = Doctrine::getTable('aPage')->createQuery('p')->select('p.id')->andWhere('p.slug LIKE ?', $params['slug_pattern']);
        aTools::$searchService->addSearchToQuery($query, $q);
        $values = $query->fetchArray();
        $pageIds = array();
        foreach ($values as $value)
        {
          $pageIds[] = $value['id'];
        }
      }
      else
      {
        if (isset($options['slugStem']))
        {
          $q = "($q) AND slug:" . $options['slugStem'];
        }
        try
        {
          $values = aZendSearch::searchLuceneWithValues(Doctrine::getTable('aPage'), $q, aTools::getUserCulture());
        } catch (Exception $e)
        {
          // Lucene search error. TODO: display it nicely if they are always safe things to display. For now: just don't crash
          $values = array();
        }
        $now = date('YmdHis');
        $pageIds = array();
        foreach ($values as $value)
        {
          // Regardless of the above if it ain't published yet we can't see it.
          // We filter on that in the Doctrine query too but take advantage of
          // this chance to preempt a little work
          if ($value->published_at > $now)
          {
            continue;
          }
          // 1.5: the names under which we store columns in Zend Lucene have changed to
          // avoid conflict with also indexing them
          $info = unserialize($value->info_stored);

          if (!aPageTable::checkPrivilege('view', $info))
          {
            continue;
          }
          $pageIds[] = $info['id'];
        }
      }

    }

    $mysql = new aMysql();

    // Select the relevant virtual pages for this engine
    $q = 'from a_page p ';
    
    // If alpha sort is present we need title slots
    if ($alphaSort)
    {
      if (!isset($options['culture']))
      {
        $options['culture'] = aTools::getUserCulture();
      }
      $culture = $options['culture'];
      $q .= "
        LEFT JOIN a_area a ON a.page_id = p.id AND a.name = 'title' AND a.culture = :culture
        LEFT JOIN a_area_version v ON v.area_id = a.id AND a.latest_version = v.version 
        LEFT JOIN a_area_version_slot avs ON avs.area_version_id = v.id
        LEFT JOIN a_slot s ON s.id = avs.slot_id ";
      $params['culture'] = $culture;
    }

    // Merge in categories. A left join unless we are firmly restricted to a list of categories
    // (engine page), in which case it never makes sense to list others. Don't do that
    // for hasCategorySlug, which just means a filter is active (this is a fix)

    $hasCategoryIds = (isset($options['categoryIds']) && count($options['categoryIds']));
    $hasCategorySlug = (isset($options['categorySlug']) && strlen($options['categorySlug']));
    // We handle a category slug separately as a WHERE condition for the queries on
    // things other than categories, at the end
    $restrictedByCategory = $hasCategoryIds;

    if ($restrictedByCategory)
    {
      $cjoin = 'inner join';
    }
    else
    {
      $cjoin = 'left join';
    }
    $q .= $cjoin . ' a_page_to_category ptc on ptc.page_id = p.id ' . $cjoin . ' a_category c on ptc.category_id = c.id ';

    // The engine page is firmly locked down to these categories. If none are specified it is not
    // locked down by category, if we're filtering by a category at the moment we'll handle that
    // with a where condition at the end but still list other categories as filter choices

    if ($hasCategoryIds)
    {
      $q .= "and c.id in :category_ids ";
      $params['category_ids'] = $options['categoryIds'];
    }

    // Bring in tags...
    $hasTag = isset($options['tag']) && strlen($options['tag']);
    
    // Fix: don't ever do an inner join here, that prevents us from getting a full
    // list of tags meeting the other criteria, we use a where condition at the end
    // in the queries for things other than tags
    
    $q .= 'left join tagging ti on ti.taggable_id = p.id and ti.taggable_model = "aPage" left join tag t on ti.tag_id = t.id ';

    // Get ready to filter posts or events chronologically

    $year = sprintf("%04d", isset($options['year']) ? $options['year'] : 0);
    $month = sprintf("%02d", isset($options['month']) ? $options['month'] : 0);
    $day = sprintf("%02d", isset($options['day']) ? $options['day'] : 0);

    $startYear = $year;
    $endYear = $year;
    if ($year > 0)
    {
      if ($month == 0)
      {
        // Do not mess up the two digit strings please
        $startMonth = '01';
        $startDay = '01';
        $endMonth = '12';
        $endDay = '31';
      }
      else
      {
        $startMonth = $month;
        $endMonth = $month;
        if ($day == 0)
        {
          // Do not mess up the two digit strings please
          $startDay = '01';
          $endDay = '31';
        }
        else
        {
          $startDay = $day;
          $endDay = $day;
        }
      }
    }
    else
    {
      // For posts "today and forward" is not a relevant concept (and a separate clause
      // already makes sure we don't see unpublished stuff). For events we'll override
      // the start date below
      
      // For compatibility with the blog importer make sure we accept 0000-00-00 as a
      // publication date
      $startYear = '0000';
      $startMonth = '00';
      $startDay = '00';
      $endYear = '9999';
      $endMonth = '12';
      $endDay = '31';
    }

    $events = isset($options['byEventDateRange']) && $options['byEventDateRange'];

    if ($events && ($startYear === '0000'))
    {
      list($startYear, $startMonth, $startDay) = preg_split('/-/', date('Y-m-d'));
    }
    
    // Insist that the virtual page still be the one associated with
    // the blog item. Otherwise we could be counting orphans as well.
    // Orphans ideally wouldn't exist, but things happen. At one point
    // I considered making this method a more universal thing beyond the blog
    // plugin, which is why I was initially reluctant to mandate this inner join, 
    // but we wind up needing it for date ranges too etc.
    $q .= ' inner join a_blog_item bi on bi.page_id = p.id ';
    
    if ($events)
    {
      // The event's start and end dates are part of the blog item table
      $q .= "and bi.start_date <= :end_date ";
      $params['end_date'] = "$endYear-$endMonth-$endDay";
      $q .= "and bi.end_date >= :start_date ";
      $params['start_date'] = "$startYear-$startMonth-$startDay";
    }
    
    $hasAuthor = (isset($options['author']) && strlen($options['author']));
    // Now join with sf_guard_user so we can get usernames & full names and also 
    // limit to a specific username where desired (we do that with a where clause
    // at the end so we can exempt the query for authors from it easily)
    $q .= 'left join sf_guard_user au on bi.author_id = au.id ';

    // Criteria for the pages themselves (only pages for the right engine)
    $q .= 'where p.slug like :slug_pattern ';

    // We often filter posts (not events) by a range of publication dates
    if (isset($options['byPublishedAt']) && $options['byPublishedAt'])
    {
      // Include time portion - published_at is a full timestamp
      
      $q .= "and p.published_at <= :p_end_date ";
      $params['p_end_date'] = "$endYear-$endMonth-$endDay 23:59:59";
      $q .= "and p.published_at >= :p_start_date ";
      $params['p_start_date'] = "$startYear-$startMonth-$startDay 00:00:00";
    }

    // In no case do we show unpublished material
    $q .= 'and p.published_at <= NOW() and (p.archived IS NULL or p.archived IS FALSE) ';

    // ... But only those matching the Lucene search that already gave us specific IDs.
    // NOTE: if pageIds is not null and is empty, NOTHING should be returned
    // (someone searched for something that doesn't appear in the system)
    if (isset($pageIds))
    {
      if (count($pageIds))
      {
        $q .= 'and p.id in :pageIds ';
        $params['pageIds'] = $pageIds;
      }
      else
      {
        $q .= 'and 0 <> 0 ';
      }
    }

    if ($alphaSort)
    {
      $pagesOrderBy = 's.value asc';
    }
    elseif ($events)
    {
      $pagesOrderBy = 'bi.start_date asc, bi.start_time asc';
    }
    else
    {
      // Oops: blog presentation is typically descending, not ascending
      $pagesOrderBy = 'p.published_at desc';
    }

    // Filter this event and add entries to the array to add more clauses to the WHERE clause of the
    // query. They will automatically be joined with AND to the standard clauses
    
    // The blog item's alias is bi. The page's alias
    // is p. The category's alias is c. The tag's alias
    // is t. This is MySQL, not Doctrine
    
    // The 'events' flag is available to your listener in the parameters of the event.
    // If true the query is being built for events rather than posts
    
    $event = sfContext::getInstance()->getEventDispatcher()->filter(new sfEvent(null, 'aBlog.addWhereClauses', array('events' => $events)), array());
    foreach ($event->getReturnValue() as $clause)
    {
      $q .= 'AND (' . $clause .') ';
    }
    
    // Filte this event and add entries to the array to add more ORDER BY clauses to the WHERE clause of the
    // query. They will automatically be prepended to the standard clauses, separated by commas

    // The blog item's alias is bi. The page's alias
    // is p. The category's alias is c. The tag's alias
    // is t. This is MySQL, not Doctrine
    
    // The 'events' flag is available to your listener in the parameters of the event.
    // If true the query is being built for events rather than posts

    $event = sfContext::getInstance()->getEventDispatcher()->filter(new sfEvent(null, 'aBlog.addOrderByClauses', array('events' => $events)), array());
    $clauses = $event->getReturnValue();
    $imploded = implode(', ', $clauses) . ' ';
    if (count($clauses))
    {
      $pagesOrderBy = $imploded . ', ' . $pagesOrderBy;
    }

    // Separate queries, but quite fast because we're not bogged down in Doctrineland
    
    $c_q = $q;
    $t_q = $q;
    $p_q = $q;
    $a_q = $q;
    
    // We are filtering by this specific category
    if ($hasCategorySlug)
    {
      // Limit tags and pages by this specific category, but don't limit
      // categories by it, otherwise we can't present a choice of categories
      // meeting the other criteria
      $p_q .= "and c.slug = :category_slug ";
      $t_q .= "and c.slug = :category_slug ";
      $a_q .= "and c.slug = :category_slug ";
      $params['category_slug'] = $options['categorySlug'];
    }
    
    if ($hasTag)
    {
      // Limit pages and categories by this specific tag, but don't limit
      // tags by it, otherwise we can't present a choice of tags
      // meeting the other criteria
      $p_q .= 'and t.name = :tag_name ';
      $c_q .= 'and t.name = :tag_name ';
      $a_q .= 'and t.name = :tag_name ';
      $params['tag_name'] = $options['tag'];
    }
    
    if ($hasAuthor)
    {
      $p_q .= 'and au.username = :username ';
      $c_q .= 'and au.username = :username ';
      $t_q .= 'and au.username = :username ';
      $params['username'] = $options['author'];
    }
        
    // In the cases where we are looking for categories or tags, be sure to
    // discard the null rows from the LEFT JOINs. This is simpler than 
    // determining when to switch them to INNER JOINs
    
    // Hydrate real Doctrine objects for authors. It ensures we can stringify them consistently,
    // and the number of authors tends to have reasonable constraints

    $authorsInfo = $mysql->query('select distinct au.username, au.id, au.first_name, au.last_name ' . $a_q . ' and au.id is not null order by au.last_name asc, au.first_name asc', $params);
    $authors = array();
    foreach ($authorsInfo as $authorInfo)
    {
      $author = new sfGuardUser();
      $author->fromArray($authorInfo);
      $authors[] = $author;
    }
    
    $result = array(
      'categoriesInfo' => $mysql->query('select distinct c.slug, c.name ' . $c_q . 'and c.slug is not null order by c.name', $params),
      'tagsByName' => $mysql->query('select t.name, count(distinct p.id) as t_count ' . $t_q . 'and t.name is not null group by t.name order by t.name', $params),
      'tagsByPopularity' => $mysql->query('select t.name, count(distinct p.id) as t_count ' . $t_q . 'and t.name is not null group by t.name order by t_count desc limit 10', $params),
      'pageIds' => $mysql->queryScalar('select distinct p.id ' . $p_q . ' order by ' . $pagesOrderBy, $params),
      'authors' => $authors);
    return $result;
  }
  
  static protected function between($x, $lo, $hi)
  {
    if ($x < $lo)
    {
      return false;
    }
    if ($x > $hi)
    {
      return false;
    }
    return true;
  }
}