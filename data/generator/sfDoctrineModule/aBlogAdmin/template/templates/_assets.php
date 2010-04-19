[?php slot('body_class') ?]a-admin [?php echo $sf_params->get('module'); ?] [?php echo $sf_params->get('action');?] [?php end_slot() ?]

  [?php use_stylesheet('/apostrophePlugin/css/a.css', 'first') ?]

  [?php use_javascript('/apostrophePlugin/js/aControls.js') ?]
  [?php use_javascript('/apostrophePlugin/js/aUI.js') ?]

  [?php use_stylesheet('/sfJqueryReloadedPlugin/css/ui-lightness/jquery-ui-1.7.2.custom.css', 'first') # JQ Date Picker Styles (This doesn't have to be the ui.all.css, we could make a custom css later ) ?]
  [?php use_javascript('/sfJqueryReloadedPlugin/js/plugins/jquery-ui-1.7.2.custom.min.js', 'last') # JQ Date Picker JS (This can/should be consolidated with sfJqueryReloadedPlugin/js/jquery-ui-sortable...) ?]

[?php aTools::setAllowSlotEditing(false); ?]
