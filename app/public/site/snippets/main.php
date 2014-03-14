<?php 
//include('connect.php');

/**
 * BDD import
 * 1. CSS3Create data (via RSS) TODO, mabe use a text file
 */

$links = array();

/*
if($r = $mysqli->query('SELECT * FROM articles WHERE source="CSS3Create"')){
	$json = '{';
	$compteur = 0;
	while($data = $r->fetch_assoc()){
		$json .= '"' . $compteur . '": {';
		foreach ($data as $key => $value) {

			// swith title and subtitle
			if ($key === 'titre') {
				$key = 'title';
			} elseif ($key === 'soustitre') {
				$key = 'subtitle';
			}

			$json .= '"' . $key . '": "' . utf8_encode($value) . '", ';
		}
		$json = substr($json, 0, strlen($json) - 2);

		$json .= '}, ';

		$compteur++;

	}
	$json = substr($json, 0, strlen($json) - 2);
	$json .= '}';
}
*/
//echo $json;

function addArticleToLinks ($data, &$links) {

	$links[strtotime($data['date'])] = $data;

}

/**
 * Kirby specifics
 * 1. Blog
 * 2. Conf
 * 3. CSS
 */

// if it's the homepage
$isHomePage = $page->isHomePage();
if ( $isHomePage ) {
	// list all pages: blog, conf, css
	$childs = $pages->find('blog','conf','css')->children()->visible();
	//print_r($childs);
	// + list all others articles
	$search = array('css3create', 'ailleurs', 'lab', 'publi');
	$data = array();
	foreach ($search as $value) {
		$json = json_decode($pages->find($value)->articles()->value, true);
		for ($i=0; $i < sizeof($json); $i++) {

			$json[$i]['title'] = unwrap(kirbytext(utf8_decode($json[$i]['title'])));
			$json[$i]['subtitle'] = (isset($json[$i]['subtitle'])) ? utf8_decode($json[$i]['subtitle']) : '';
			$json[$i]['image'] = (isset($json[$i]['image'])) ? $json[$i]['image'] : '';
			$json[$i]['source'] = $value;

			// add to $links
			addArticleToLinks($json[$i], $links);
		}
	}

// else if it's tagged's page
} elseif ( $page->uri === 'tagged') {
	// what the tag
	foreach ($_GET as $key => $value) {
		$tag = $key;
	}
	// list all pages with the tag
	$childs = $pages->find('blog', 'conf')->children()->visible()
                  ->filterBy('tags', $tag, ',');

// else if we are in a special category (with others articles)
} elseif ( $page->uri === 'css3create' || $page->uri === 'ailleurs' || $page->uri === 'lab' || $page->uri === 'publi') {

	$childs = array();

	$json = json_decode($page->articles()->value, true);
	for ($i=0; $i < sizeof($json); $i++) {

		$json[$i]['title'] = unwrap(kirbytext(utf8_decode($json[$i]['title'])));
		$json[$i]['subtitle'] = (isset($json[$i]['subtitle'])) ? utf8_decode($json[$i]['subtitle']) : '';
		$json[$i]['image'] = (isset($json[$i]['image'])) ? $json[$i]['image'] : '';
		$json[$i]['source'] = $page->uri;

		// add to $links
		addArticleToLinks($json[$i], $links);
	}

// else, we are in a simple category
}else {
	$childs = $page->children->visible();
}
//echo 'toto';
//print_r($links);

/**
 * get infos of all pages
 */
foreach($childs as $child) {

	if (is_array($child)) {
		
		$data = $child;

	} else {

		$subtitle = ( isset($child->subtitle()->value) ) ? $child->subtitle()->value : '';
		$image = ( isset($child->image()->value) ) ? $child->image()->value : false;
		if ($image) {
			$image = $child->files->find($image)->url();
		}
		$source = $child->parent()->uri;
		$big = ( isset($child->big()->value) ) ? $child->big()->value : 0;


		$data = array(
			'title' => utf8_decode($child->title()->value),
			'subtitle' => utf8_decode($subtitle),
			'url' => $child->url(),
			'date' => $child->date('Y-m-d H:i:s'),
			'source' => $source,
			'image' => $image,
			'big' => $big
		);

	}

	addArticleToLinks($data, $links);
	//$links[strtotime($data['date'])] = $data;
}

/**
 * All items are here, just sort them by date
 */
krsort($links);

//echo 'tata';
//print_r($links);
 ?>

<section class="main" role="main">
	<ul class="wrap Greeed">
	<?php 
	$mois = array('janv', 'févr', 'mars', 'avril', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc');
	$today = new DateTime();
	foreach($links as $content){
		//print_r($content);
		$element = 'item';
		$title = utf8_encode($content['title']);
		$subtitle = utf8_encode($content['subtitle']);
		$url = $content['url'];
		$date = $content['date'];
		$source = strtolower($content['source']);
		$media = $content['image'];
		$big = $content['big'];

		// styles
		$styleBgImage = ($media) ? 'background-image: url(' . $media . ')' : '';
		
		// classes
		$classModifier = $element . '--' . $source;
		//if ($source == 'ailleurs') { $classModifier .= ' ' . $element . '--blog'; $source = 'blog'; }
		$classImg = ($media) ? $element . '--media' : '';
		$classBig = ($big) ? $element . '--big' : '';

		// date
		$time = new DateTime($date);
		$time = $time->getTimestamp();

		$new = ( (time() - $time) < 60*60*24*12 );

		?>
		<li class="<?php echo $element . ' ' . $classModifier . ' ' . $classImg . ' ' . $classBig; ?>">
			<a class="<?php echo $element . '-link'; ?>" href="<?php echo $url; ?>">
				<?php 
				// IS_MEDIA and IS_SVG
				$IS_MEDIA = ( ( !$big && ( $media OR $source === 'conf' ) ) OR ( $big ) );
				$IS_SVG = ( ( $big && !$media ) OR ( !$big && !$media && $source === 'conf' ) );

				// IS_MEDIA: add a media
				if ( $IS_MEDIA ) {
					?>
					<span class="item-media <?php echo ($IS_SVG) ? 'item-media--svg' : 'item-media--nosvg';  ?>" style="<?php echo $styleBgImage; ?>">
					<?php 
					// IS_SVG: add a SVG
					if ( $IS_SVG ) {
					?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" class="svg-icon svg-icon--<?php echo $source; ?>">
							<use xlink:href="#svg-<?php echo $source; ?>" width="100" height="100"></use>
						</svg>
					<?php
					}
					?>
					</span>
				<?php 
				} 
				?>
				<time class="<?php echo $element . '-date'; ?>">
					<?php 
					echo setDateFr($time);
					if ($new) {
						?>
						<span class="<?php echo $element . '-date-new'; ?>" style="font: normal .75em sans-serif;
	color: #FFF;
	background: #f03d36;
	margin-left: 1em;
	padding: .1em .5em;
	border-radius: 2px;">RÉCENT</span>
						<?php
					}?>
				</time>
				<span class="item-title"><?php echo $title; ?></span>
				<span class="item-subtitle"><?php echo $subtitle; ?></span>
			</a>
			<!--[if !IE]><!-->
			<svg xmlns="http://www.w3.org/2000/svg" class="item-effect">
				<rect width="100%" height="100%" fill="none"></rect>
			</svg>
			<!--<![endif]-->
		</li>
	<?php
	}	
	 ?>
	</ul>
</section>