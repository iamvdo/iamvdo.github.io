<?php 

$articles = $pages->find('blog', 'conf')->children()->visible()->sortBy('date', 'desc')->filter(function($child) {
	$ownLang = $child->content()->language();
	if ($ownLang == '') {
		$ownLang = site()->defaultLanguage()->code();
	}
	return $ownLang == site()->language()->code();
})->limit(10);

snippet('rss-template', array(
  'link'  => url('/'),
  'items' => $articles,
  'descriptionField'  => 'text', 
  'descriptionLength' => false,
  'descriptionExcerpt' => false
));

?>