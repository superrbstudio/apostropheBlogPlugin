<p>
  <?php // Google says they accept HTML in descriptions but they seem not to use it for anything. ?>
  <?php // You never see descriptions anyway except when editing them. So send the plaintext. ?>
  <?php // The byte limit was chosen to avoid creating a URL that the browser won't accept, ?>
  <?php // even when Google double-encodes it in some situations ?>
  <?php $a_event = $sf_data->getRaw('a_event') ?>
  <?php echo link_to('Add to Google Calendar', 'http://www.google.com/calendar/event?' . http_build_query(array('action' => 'TEMPLATE', 'text' => $a_event->getTitle(), 'dates' => $a_event->getUTCDateRange(), 'location' => preg_replace('/\s+/', ' ', $a_event['location']), 'sprop' => 'website:' . $sf_request->getHost(), 'details' => aHtml::toPlaintext($a_event->getTextForArea('blog-body', 500))))) ?> 
</p>
