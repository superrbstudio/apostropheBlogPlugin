<?php
require_once dirname(__FILE__).'/aBlogAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/aBlogAdminGeneratorHelper.class.php';
/**
 * Base actions for the aBlogPlugin aBlogAdmin module.
 * 
 * @package     aBlogPlugin
 * @subpackage  aBlogAdmin
 * @author      Dan Ordille <dan@punkave.com>
 */
abstract class BaseaBlogAdminActions extends autoABlogAdminActions
{
  public function preExecute()
  {
    parent::preExecute();
    if (sfConfig::get('app_aBlog_use_bundled_assets', true))
    {
      $this->getResponse()->addJavascript('/apostropheBlogPlugin/js/aBlog.js');
    }
  }

  // You must create with at least a title
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404();
  }
  
  // Doctrine collection routes make it a pain to change the settings
  // of the standard routes fundamentally, so we provide another route
  public function executeNewWithTitle(sfWebRequest $request)
  {
    $this->form = new aBlogNewPostForm();
    $this->form->bind($request->getParameter('a_blog_new_post'));
    if ($this->form->isValid())
    {
      $this->a_blog_post = new aBlogPost();
      $this->a_blog_post->Author = $this->getUser()->getGuardUser();
      $this->a_blog_post->setTitle($this->form->getValue('title'));
      $this->a_blog_post->save();
      $this->postUrl = $this->generateUrl('a_blog_admin_edit', $this->a_blog_post);
      return 'Success';
    }
    return 'Error';
  }
    
  // DEPRECATED. use the new search method which is powered by Lucene
  public function executeAutocomplete(sfWebRequest $request)
  {
    $this->aBlogPosts = aBlogItemTable::titleSearch($request->getParameter('q'), '@a_blog_search_redirect');
    $this->setLayout(false);
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
    error_log("In executeUpdate");
    $this->setABlogPostForUser();
    $this->form = new aBlogPostForm($this->a_blog_post);
    if ($request->getMethod() === 'POST')
    {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid())
      {
        $this->a_blog_post = $this->form->save();
        // Recreate the form to get rid of bound values for the publication field,
        // so we can see the new setting
        $this->form = new aBlogPostForm($this->a_blog_post);
      }
    }
    if (!$request->isXmlHttpRequest())
    {
      $this->setTemplate('edit');
    }
  }
  
  public function executeUpdateTitle(sfWebRequest $request)
  {
    $this->setABlogPostForUser();
    $title = trim($request->getParameter('title'));
    if (strlen($title))
    {
      // The preUpdate method takes care of updating the slug from the title as needed
      $this->a_blog_post->setTitle($title);
      $this->a_blog_post->save();
    }
    $this->setTemplate('titleAndSlug');
  }

  public function executeUpdateSlug(sfWebRequest $request)
  {
    $this->setABlogPostForUser();
    $slug = trim($request->getParameter('slug'));
    if (strlen($slug))
    {
      error_log("Setting the slug to $slug");
      // "OMG, aren't you going to slugify this?" The preUpdate method of the
      // PluginaBlogItem class takes care of slugifying and uniqueifying the slug.
      $this->a_blog_post->setSlug($slug);
      $this->a_blog_post->save();
    }
    else
    {
      error_log("Not setting the slug");
    }
    $this->setTemplate('titleAndSlug');
  }
  
  protected function setABlogPostForUser()
  {
    if ($this->getUser()->hasCredential('admin'))
    {
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
  }

  public function executeRedirect()
  {
    $aBlogPost = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($aBlogPost->findBestEngine());
    $this->redirect($this->generateUrl('a_blog_post', $this->getRoute()->getObject()));
  }

  public function executeCategories()
  {
    $this->redirect('@a_category_admin');
  }

  public function executeIndex(sfWebRequest $request)
  {
    if(!aPageTable::getFirstEnginePage('aBlog'))
    {
      $this->setTemplate('engineWarning');
    }

    parent::executeIndex($request);
    aBlogItemTable::populatePages($this->pager->getResults());
  }

  public function executeEdit(sfWebRequest $request)
  {
		$this->getResponse()->addJavascript('/sfDoctrineActAsTaggablePlugin/js/pkTagahead.js','last');
	
    if($this->getUser()->hasCredential('admin'))
    {
      $this->a_blog_post = $this->getRoute()->getObject();
    }
    else
    {
      $this->a_blog_post = Doctrine::getTable('aBlogPost')->findOneEditable($request->getParameter('id'), $this->getUser()->getGuardUser()->getId());
    }
    $this->forward404Unless($this->a_blog_post);
    // Separate forms for separately saved fields
    $this->form = new aBlogPostForm($this->a_blog_post);

		// Retrieve the tags currently assigned to the blog post for the inlineTaggableWidget
		$this->existingTags = $this->form->getObject()->getTags();
		// Retrieve the 10 most popular tags for the inlineTaggableWidget
    $this->popularTags = TagTable::getAllTagNameWithCount(null, array('model' => 'aBlogPost', 'sort_by_popularity' => true), false, 10);

    aBlogItemTable::populatePages(array($this->a_blog_post));
  }

  protected function buildQuery()
  {
    if (is_null($this->filters))
    {
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }
    $filters = $this->getFilters();
    $resetFilters = false;
    foreach($this->filters->getAppliedFilters() as $name => $field)
    {
      foreach($field as $key => $value)
      {
        if(is_null($value))
        {
          unset($filters[$name]);
          $resetFilters = true;
        }
      }
    }
    if($resetFilters)
    {
      $this->getUser()->setAttribute('aBlogAdmin.filters', $filters, 'admin_module');
      $this->filters = $this->configuration->getFilterForm($this->getFilters());
    }

    $query = parent::buildQuery();
    $query->leftJoin($query->getRootAlias().'.Author')
      ->leftJoin($query->getRootAlias().'.Editors')
      ->leftJoin($query->getRootAlias().'.Categories')
      ->leftJoin($query->getRootAlias().'.Page');
    return $query;
  }
  
  public function executeRemoveFilter(sfWebRequest $request)
  {
    $name = $request->getParameter('name');
    $value = $request->getParameter('value');

    $filters = $this->getUser()->getAttribute('aBlogAdmin.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    unset($filters[$name]);
    $this->getUser()->setAttribute('aBlogAdmin.filters', $filters, 'admin_module');

    $this->redirect('@a_blog_admin');
  }
  
  // Unlike search in the engine, this is not specific to the categories of the page
  // Pass a 'term' argument rather than a 'q' argument for a nice jquery.autocomplete friendly AJAX array
  // with 'label' and 'value'
  public function executeSearch(sfWebRequest $request)
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
        return $this->redirect(sfContext::getInstance()->getController()->genUrl('aBlog/search', true) . '?' . http_build_query(array("q" => $q)));
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
      $q = "($q) AND slug:@a_blog_redirect";
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

      if ($value->published_at > $now)
      {
        error_log("Not published: " . $value->title);
        continue;
      }
      if (!aPageTable::checkPrivilege('view', $info))
      {
        error_log("Not viewable: " . $value->title);
        continue;
      }
      $nvalue = $value;
      $nvalue->page_id = $info['id'];
      $nvalue->slug = $nvalue->slug_stored;
      $nvalue->title = $nvalue->title_stored;
      $nvalue->summary = $nvalue->summary_stored;
      // Virtual page slug is a named Symfony route, it wants search results to go there
      $nvalue->url = $this->getController()->genUrl($nvalue->slug, true);
      $nvalue->class = 'aBlog';
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
      $this->results = array();
      if (count($pageIds))
      {
        $infos = Doctrine::getTable('aBlogPost')->createQuery('p')->select('p.id, p.page_id, p.status')->whereIn('p.page_id', $pageIds)->fetchArray();
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
            $this->results[] = array('label' => $value->title, 'value' => $pageIdToPostId[$value->page_id]);
          }
        }
      }
      return 'Autocomplete';
    }
    $this->pager = new aArrayPager(null, sfConfig::get('app_a_search_results_per_page', 10));    
    $this->pager->setResultArray($values);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();
    $this->pagerUrl = "aBlog/search?" . http_build_query(array("q" => $q));
    // setTitle takes care of escaping things
    $this->getResponse()->setTitle(aTools::getOptionI18n('title_prefix') . 'Search for ' . $q . aTools::getOptionI18n('title_suffix'));
    $this->results = $this->pager->getResults();
  }
}
