<?php

// defaults
if(!isset($descriptionExcerpt)) $descriptionExcerpt = true; 

// send the right header
header('Content-type: text/xml; charset="utf-8"');

// echo the doctype
echo '<?xml version="1.0" encoding="utf-8"?>';

date_default_timezone_set('Europe/Paris');
?>

<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">

  <channel>
    <title><?php echo (isset($title)) ? xml($title) : xml($site->title()) ?></title>
    <link><?php echo $site->full_url() . $site->homePage()->url(); ?></link>
    <lastBuildDate><?php 
      echo (reset($items)['modified']) ? date('D, d M Y H:i:s T', reset($items)['modified']) : date('D, d M Y H:i:s T', $site->modified());
       ?></lastBuildDate>
    <atom:link href="<?php echo xml(thisURL()) ?>" rel="self" type="application/rss+xml" />
  
    <?php foreach($items as $item): ?>
    <item>
      <title><?php echo xml($item['title']) ?></title>
      <link><?php echo xml($item['url']) ?></link>
      <guid><?php echo xml($item['url']) ?></guid>
      <pubDate><?php echo date('D, d M Y H:i:s T', $item['modified']); ?></pubDate>
        
      <?php if(isset($descriptionField)): ?>
      <?php if(!$descriptionExcerpt): ?>
      <description><![CDATA[<?php echo kirbytext($item['text']) ?>]]></description>
      <?php else: ?>
      <description><![CDATA[<?php echo excerpt($item['text'], (isset($descriptionLength)) ? $descriptionLength : 140) ?>]]></description>
      <?php endif ?>
      <?php endif ?>

    </item>
    <?php endforeach ?>
        
  </channel>
</rss>