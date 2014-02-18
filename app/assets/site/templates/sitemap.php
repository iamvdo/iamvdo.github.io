<?php 

// send the right header
header('Content-type: text/xml; charset="utf-8"');

// echo the doctype
echo '<?xml version="1.0" encoding="utf-8"?>';

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<?php 
// pages inside blog, conf
$sitemap = $pages->find('blog','conf')->children()->visible()->sortBy('date', 'desc');
foreach($sitemap as $p){
?> 
	<url>
		<loc><?php echo html($site->full_url() . $p->url()) ?></loc>
		<lastmod><?php echo $p->modified('c') ?></lastmod>
		<priority><?php echo ($p->isHomePage()) ? 1 : number_format(0.5/$p->depth(), 1) ?></priority>
	</url>
<?php 
}

// pages blog, conf and home
$sitemap = $pages->find('blog','conf', 'home')->sortBy('date', 'desc');
foreach($sitemap as $p){
?> 
	<url>
		<loc><?php echo html($site->full_url() . $p->url()) ?></loc>
		<lastmod><?php echo $p->modified('c') ?></lastmod>
		<priority><?php echo ($p->isHomePage()) ? 1 : number_format(0.5/$p->depth(), 1) ?></priority>
	</url>
<?php 
}

// pages inside lab
$json = json_decode($pages->find('lab')->articles()->value, true);
for ($i=0; $i < sizeof($json); $i++) {
?> 
	<url>
		<loc><?php echo html($json[$i]['url']); ?></loc>
		<lastmod><?php echo $json[$i]['date']; ?></lastmod>
	</url>
<?php 
}
?>

</urlset>