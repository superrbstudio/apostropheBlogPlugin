<?php if ($sf_params->get('tag') || $sf_params->get('cat') || $sf_params->get('search')): ?>
<p class="blog-errors">Sorry, but there aren't any events in this date range that match. Try browsing other dates, tags and categories with the navigation on the left.</p>
<?php else: ?>
<p class="blog-errors">Sorry, but there aren't any events in this date range. Try browsing other dates with the navigation on the left.</p>
<?php endif ?>