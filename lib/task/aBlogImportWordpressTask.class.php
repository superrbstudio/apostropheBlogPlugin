<?php

class aBlogImportWordpressTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('xml', null, sfCommandOption::PARAMETER_REQUIRED, 'An XML file created by the Wordpress export feature', null)
      // add your own options here
    ));

    $this->namespace = 'apostrophe';
    $this->name = 'import-wordpress';
    $this->briefDescription = 'Imports a blog from a Wordpress XML export';
    $this->detailedDescription = <<<EOF
Usage:

php symfony apostrophe:import-wordpress --xml=wordpress-xml-export-file.xml
EOF;
  }

  protected function execute($args = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getDoctrineConnection();
    if (is_null($options['xml']))
    {
      echo("Required option --xml=filename not given. Generate a Wordpress export XML file first.\n");
      exit(1);
    }
    $xml = simplexml_load_file($options['xml']);
    $channel = $xml->channel[0];
    $out = <<<EOM
<?xml version="1.0" encoding="UTF-8"?>
<posts>
EOM
;
    $statusWarn = false;
    foreach ($xml->channel[0]->item as $item)
    {
      $tags = array();
      $categories = array();
      $wpXml = $item->children('http://wordpress.org/export/1.0/');
      $title = $this->escape($item->title[0]);
      // In our exports pubDate was always wrong (the same value for every item)
      // so post_date was a much more reasonable value
      $published_at = $this->escape($wpXml->post_date[0]);
      $slug = $this->escape($wpXml->post_name[0]);
      $status = $this->escape($wpXml->status[0]);
      $link = $this->escape($item->link[0]);
      $contentXml = $item->children('http://purl.org/rss/1.0/modules/content/');
      $body = $this->escape($contentXml->encoded[0]);
      // Blank lines = paragraph breaks in Wordpress. This is difficult to translate
      // to Apostrophe cleanly because it's nontrivial to turn them into nice
      // paragraph containers. Go with a double br to get the same effect for now
      $body = preg_replace('/(\r)?\n(\r)?\n/', "\r\n&lt;br /&gt;&lt;br /&gt;\r\n", $body);
      if ($status === 'draft')
      {
        if (!$statusWarn)
        {
          echo("WARNING: unpublished drafts are not imported\n");
          $statusWarn = true;
        }
        continue;
      }
      foreach ($item->category as $category)
      {
        $domain = (string) $category['domain'];
        if ($domain === 'tag')
        {
          $tags[] = (string) $category;
        }
        elseif ($domain === 'category')
        {
          $categories[] = (string) $category;
        }
      }
      $out .= <<<EOM
  <post published_at="$published_at" slug="$slug">
    <title>$title</title>
    <categories>
    
EOM
;
      foreach ($categories as $category)
      {
        $out .= "      <category>" . $this->escape($category) . "</category>\n";
      }
      $out .= <<<EOM
    </categories>
    <tags>
    
EOM
;
      foreach ($tags as $tag)
      {
        $out .= "      <tag>" . $this->escape($tag) . "</tag>\n";
      }
      $out .= <<<EOM
    </tags>
    <Page>
      <Area name="blog-body">
        <Slot type="foreignHtml">
          <value>$body</value>
        </Slot>
      </Area>
    </Page>
  </post>
EOM
;
    }
    $out .= <<<EOM
</posts>
EOM
;
    $ourXml = aFiles::getTemporaryFilename();
    file_put_contents($ourXml, $out);
    file_put_contents('data/test.xml', $out);
    $task = new aBlogImportTask($this->dispatcher, $this->formatter);
    $task->run(array(), array('posts' => $ourXml, 'env' => $options['env'], 'connection' => $options['connection']));
    unlink($ourXml);
  }
  
  public function escape($s)
  {
    // Yes, we really mean it when we double-encode here
    return htmlspecialchars((string) $s, ENT_COMPAT, 'UTF-8', true);
  }
}