function aBlogEnableTitle()
{
  apostrophe.formUpdates({ 'selector': '#a-blog-item-title-interface', 'update': 'a-blog-title-and-slug' }); 

  // Form has class, input element has id
  var titleInterface = $('#a-blog-item-title-interface');
  var tControls = titleInterface.find('ul.a-controls');
  var tInput = titleInterface.find('.a-title');
  var originalTitle = tInput.val();
  tInput.keyup(function(event) {
    if (tInput.val().trim() != originalTitle.trim())
    {
      titleInterface.addClass('has-changes');
      tControls.fadeIn();
    }
    return false;
  });
  titleInterface.find('.a-cancel').click(function() {
    tInput.val(originalTitle);
    tControls.hide();
    return false;
  });
}

function aBlogEnableSlug()
{
  apostrophe.formUpdates({ 'selector': '#a-blog-item-permalink-interface', 'update': 'a-blog-title-and-slug' }); 
  
  // Form has class, input element has id
  var slugInterface = $('#a-blog-item-permalink-interface');
  var tControls = slugInterface.find('ul.a-controls');
  var tInput = slugInterface.find('.a-slug');
  var originalSlug = tInput.val();
  tInput.keyup(function(event) {
    if (tInput.val().trim() != originalSlug.trim())
    {
      slugInterface.addClass('has-changes');
      tControls.fadeIn();
    }
    return false;
  });
  slugInterface.find('.a-cancel').click(function() {
    tInput.val(originalSlug);
    tControls.hide();
    return false;
  });
}

function aBlogUpdateComments(enabled, feedback)
{
  if (enabled)
  {
    $('.section.comments .allow_comments_toggle').addClass('enabled').removeClass('disabled');
  }
  else
  {
    $('.section.comments .allow_comments_toggle').addClass('disabled').removeClass('enabled');    
  }
}

function aBlogEnableNewForm()
{
  var newForm = $('.a-blog-admin-new-form');
  newForm.submit(function() {
    var form = $(this);
    $.post(form.attr('action'), $(this).serialize(), function(data) {
      $(document).append(data); 
    });
    return false;
  });
}

function aBlogEnableForm(options)
{
  var savedState = null;
  var form = $('#a-admin-form');
  form.data('changed', false);
  
  apostrophe.formUpdates({ selector: '#a-admin-form', update: 'a-admin-form' });
  // Due to the way our markup is structured this is a better place for the little
  // 'updating' message
  $('.a-subnav-wrapper').addClass('a-ajax-attach-updating');
  var status = form.find('[name="a_blog_item[publication]"]');
  var init = true;
  
  // A convenience within this closure to keep us from getting lazy and
  // using selectors that aren't specific to the form
  function find(sel)
  {
    return form.find(sel);
  }
  
  status.change(function() {
    var c = form.find('.a-published-at-container');
    var s = status.val();
    if (s === 'schedule') 
    {
      c.show();
    }
    else
    {
      c.hide();
    }
    if (!init)
    {
      find('.a-save-blog-main .label').text(options['update-labels'][s]);
    }
    if (!init)
    {
      form.data('changed', true);
    }
    init = false;
  });
  status.change();

  // On various fields, including the progressively enhanced fields whose PE code has been upgraded
  // to send change events, just monitor change() and set the changed flag so it can be checked
  // by onbeforeunload
  
  form.find('#a_blog_item_location,#a_blog_item_all_day,#a_blog_item_start_date_month,#a_blog_item_start_date_day,#a_blog_item_start_date_year,#a_blog_item_start_time_hour,#a_blog_item_start_time_minute,#a_blog_item_end_date_month,#a_blog_item_end_date_day,#a_blog_item_end_date_year,#a_blog_item_end_time_hour,#a_blog_item_end_time_minute,#a_blog_item_published_at_hour,#a_blog_item_published_at_minute,#a_blog_item_author_id,#a-blog-post-tags-input').change(function() {
    form.data('changed', true);
  });

  // Listen to keystrokes in the location field. In Chrome at least, text fields don't get a 
  // change() event when you type in them and then click a link elsewhere on the page that leaves
  // the page, so we need something else
  form.find('#a_blog_item_location').keyup(function() {
    form.data('changed', true);
  });
  
  find('.template.section select').change(function() {
    alert(options['template-change-warning']);
    // Let the form submit as a full refresh. Don't complain of unsaved changes when we're about to save changes
    $(form).data('changed', false);
    $(form).unbind('submit.aFormUpdates');
  });
  
  find('.post-editors-toggle').click(function() {
    find('.post-editors-options').show();
    find('.post-editors-toggle').hide();
    return false;
  });
  
  var p = { 'choose-one': options['editors-choose-label'], 'onChange': function() {
    form.data('changed', true);
  } };
  aMultipleSelect('#editors-section', p);
  p = { 'choose-one': options['categories-choose-label'], 'onChange': function() {
    form.data('changed', true);
  } };
  if (options['categories-add'])
  {
    p['add'] = options['categories-add-label'];
  }
  aMultipleSelect(form.find('#categories-section'), p);
  
  function toggleAllDay(checkbox) {
    $(checkbox).toggleClass('all_day_enabled');
    find('.start_time').toggleClass('time_disabled').toggle();
    find('.end_time').toggleClass('time_disabled').toggle();
  }
  
  find('.all_day input[type=checkbox]').bind('click', function() {
    toggleAllDay($(this));
  });
  
  if (find('.all_day input[type=checkbox]:checked').length)
  {
    toggleAllDay($(this));
  }
  $('#a_blog_item_start_date_jquery_control').bind('aDateUpdated', checkEndDateAndTime);
  $('#a_blog_item_start_time_timepicker').bind('aTimeUpdated', checkEndDateAndTime);

  function checkEndDateAndTime() {
    // If the user changes the start date and it is now after the end date,
    // reset the end date to the current date
    var startDate = $('#a_blog_item_start_date_jquery_control').val();
    var components = startDate.split('/');
    var startCompare;
    if (components.length === 3)
    {
      startCompare = pad(components[0]) + '/' + pad(components[1]) + '/' + pad(components[2]);
    }
    var end = $('#a_blog_item_end_date_jquery_control');
    var endDate = end.val();
    var components = endDate.split('/');
    var endCompare;
    if (components.length === 3)
    {
      endCompare = pad(components[0]) + '/' + pad(components[1]) + '/' + pad(components[2]);
    }
    if (startCompare && (endCompare < startCompare))
    {
      end.val(startDate);
      a_blog_item_end_date_update_linked(startDate);
      // Push the end time to one hour after the start time on the new day.
      // If the start hour was 11pm push the end time to 11:59pm - it's not perfect but
      // it's consistent and simple to maintain
      if (!$('#a_blog_item_all_day').attr('checked'))
      {
        fix_end_time();
      }
    }
    if (startCompare && (startCompare == endCompare))
    {
      if (!$('#a_blog_item_all_day').attr('checked'))
      {
        var start_when = $('#a_blog_item_start_time_hour').val() * 60 + $('#a_blog_item_start_time_minute').val() - 0;
        var end_when = $('#a_blog_item_end_time_hour').val() * 60 + $('#a_blog_item_end_time_minute').val() - 0;
        if (start_when > end_when)
        {
          fix_end_time();
        }
      }
    }
    function fix_end_time()
    {
      if ($('#a_blog_item_start_time_hour').val() == 23)
      {
        $('#a_blog_item_end_time_hour').val(23);
        $('#a_blog_item_end_time_minute').val(59);
      }
      else
      {
        $('#a_blog_item_end_time_hour').val(parseInt($('#a_blog_item_start_time_hour').val()) + 1);
        $('#a_blog_item_end_time_minute').val($('#a_blog_item_start_time_minute').val());
      }
      $('#a_blog_item_end_time_hour').trigger('updatePicker');
    }
    function pad(n)
    {
      var s = n + '';
      if (s.length < 2)
      {
        s = '0' + s;
      }
      return s;
    }
  }
}

function aBlogGetPostStatus()
{
  var postStatus = $('#a_blog_item_status');
  return postStatus.val();
}

// Starting to assemble the aBlogConstructor -- eventually all of the JS functions above can be migrated into this space

function aBlogConstructor() 
{ 
  this.sidebarEnhancements = function(options)
  {
    var debug = options['debug'];
    
    debug ? apostrophe.log('aBlog.sidebarEnhancements -- debug') : '';
    
    $('.a-tag-sidebar-title.all-tags').click(function(){
      $('.a-tag-sidebar-list.all-tags').slideToggle();
      $(this).toggleClass('open');
    });

    $('.a-tag-sidebar-title.all-tags').hover(function(){
      $(this).toggleClass('over');
    },
    function(){
      $(this).toggleClass('over');    
    }); 
  };
  
  this.slotEditView = function(options)
  {
    var formName = options['formName'];
    var autocompleteUrl = options['autocompleteUrl'];
    var className = options['class'];
    var selfLabelSelector = options['selfLabelSelector'];
    
    var debug = (options['debug']) ? options['debug'] : false;
    
    (debug) ? apostrophe.log('aBlog.slotEditView -- formName: ' + formName) : '';
    (debug) ? apostrophe.log('aBlog.slotEditView -- autocompleteUrl: ' + autocompleteUrl) : '';
    (debug) ? apostrophe.log('aBlog.slotEditView -- class: ' + className) : '';
        
    aMultipleSelect('#a-' + formName + ' .' + className, { 'autocomplete': autocompleteUrl });
    aMultipleSelect('#a-' + formName + ' .categories', { 'choose-one': 'Add Categories' });
    
    var slotEditForm = $('#a-'+formName)
    var editStates = slotEditForm.find('.a-form-row.by-type input[type="radio"]');
    var editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
    slotEditForm.addClass('a-ui a-options dropshadow editState-' + editState );
      editStates.live('click', function(){
        editState = slotEditForm.find('.a-form-row.by-type input[type="radio"]:checked').val();
        slotEditForm.removeClass('editState-title').removeClass('editState-tags').addClass('editState-'+editState);
        if (editState === 'title') 
        {
          slotEditForm.find('.a-form-row.title .ui-autocomplete-input').focus();
        }
    });
  };

  // Autocomplete tag filter for the admin, an optional alternative
  // to the dropdown menu for sites with many, many tags.

  this.tagAutocomplete = function(tags) {
    // Don't fight with the old school dropdown, just replace it.
    var oldTagsButton = $('th.a-column-tags_list');
    var tagsWrapper = $('th.a-blog-tag-autocomplete-wrapper');
    tagsWrapper.detach();
    oldTagsButton.replaceWith(tagsWrapper);
    $("#a-blog-tag-autocomplete").autocomplete({
      source: function(req, response) {
        var re = $.ui.autocomplete.escapeRegex(req.term);
        var result = $.grep(tags, function(tag) {
            return tag.substr(0, req.term.length).toLowerCase() === req.term.toLowerCase();
        });
        return response(result);
      },
      select: function(event, ui) {
        var tag = ui.item.value;
        // It's a bit ridiculous, but we want to use a standard Symfony
        // link helper with the post option, so hotwire it
        var $link = $('#a-blog-tag-autocomplete-link a');
        $link.attr('href', $link.attr('href').replace('DUMMY', encodeURIComponent(tag)));
        $link.click();
        return false;
      }
    });
  };
}

window.aBlog = new aBlogConstructor();
