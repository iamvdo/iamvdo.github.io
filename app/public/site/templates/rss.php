<?php 

$articles = $pages->find('blog', 'conf')->children()->visible()->sortBy('date', 'desc')->limit(10);

snippet('rss-template', array(
  'link'  => url('/'),
  'items' => $articles,
  'descriptionField'  => 'text', 
  'descriptionLength' => false,
  'descriptionExcerpt' => false
));

?>