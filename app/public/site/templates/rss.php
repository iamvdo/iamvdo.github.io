<?php 

$myPages = array();

$search = array('design');
foreach ($search as $value) {
  $json = json_decode($pages->find($value)->articles()->value, true);
  for ($i=0; $i < sizeof($json); $i++) {
    $time = strtotime($json[$i]['date']);
    $myPages[$time]['title'] = unwrap(kirbytext($json[$i]['title']));
    $myPages[$time]['modified'] = $time;
    $myPages[$time]['url'] = $json[$i]['url'];
    $myPages[$time]['text'] = (isset($json[$i]['subtitle']) && $json[$i]['subtitle'] !== '') ? $json[$i]['subtitle'] . ' - ' : '';
    $myPages[$time]['text'] .= $myPages[$time]['url'];
  }
}

$articles = $pages->find('blog', 'conf')->children()->visible()->sortBy('date', 'desc')->filter(function($child) {
  $ownLang = $child->content()->language();
	if ($ownLang == '') {
		$ownLang = site()->defaultLanguage()->code();
	}
  return $ownLang == site()->language()->code();
})->filter(function ($child) {
  return time() - $child->date() > 0;
})->limit(10)->toArray();

foreach ($articles as $article) {
  $time = strtotime($article['content']['date']);
  $myPages[$time]['title'] = $article['content']['title'];
  $myPages[$time]['modified'] = $time;
  $myPages[$time]['url'] = $site->full_url() . $article['url'];
  $relativeUrl = $site->full_url() . $article['contentUrl'];
  $article['content']['text'] = preg_replace('/\(image\:\s?(\S+)/', '(image:' . $relativeUrl . '/$1',$article['content']['text']);
  $myPages[$time]['text'] = $article['content']['text'];
}

// sort by date (key), then limit 10
krsort($myPages);
$myPages = array_slice($myPages, 0, 10);

snippet('rss-template', array(
  'link'  => url('/'),
  'items' => $myPages,
  'descriptionField'  => 'text', 
  'descriptionLength' => false,
  'descriptionExcerpt' => false
));

?>