<?php

$links = array();
$currentLang = $site->language()->code();

function addArticleToLinks ($data, &$links, $lang) {
	if ($data['lang'] === $lang) {
		$links[strtotime($data['date'])] = $data;
	}
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

	// + list all others articles
	$search = array('css3create', 'ailleurs', 'code', 'publi', 'design');
	$data = array();
	foreach ($search as $value) {
		$json = json_decode($pages->find($value)->articles()->value, true);
		for ($i=0; $i < sizeof($json); $i++) {

			$json[$i]['title'] = unwrap(kirbytext(utf8_decode($json[$i]['title'])));
			$json[$i]['subtitle'] = (isset($json[$i]['subtitle'])) ? utf8_decode($json[$i]['subtitle']) : '';
			$json[$i]['image'] = (isset($json[$i]['image'])) ? $json[$i]['image'] : '';
			$json[$i]['source'] = $value;

			if (!isset($json[$i]['lang'])) {
				$json[$i]['lang'] = 'fr';
			}

			// add to $links
			addArticleToLinks($json[$i], $links, $currentLang);
		}
	}

// else if it's tagged's page
} elseif ( $page->uri() === 'tagged') {
	// what the tag
	foreach ($_GET as $key => $value) {
		$tag = $key;
	}
	// list all pages with the tag
	$childs = $pages->find('blog', 'conf')->children()->visible()
				  ->filterBy('tags', $tag, ',');

// else if we are in a special category (with others articles)
} elseif ( $page->uri() === 'css3create' || $page->uri() === 'ailleurs' || $page->uri() === 'code' || $page->uri() === 'publi' || $page->uri() === 'design') {

	$childs = array();

	$json = json_decode($page->articles()->value, true);
	for ($i=0; $i < sizeof($json); $i++) {

		$json[$i]['title'] = unwrap(kirbytext(utf8_decode($json[$i]['title'])));
		$json[$i]['subtitle'] = (isset($json[$i]['subtitle'])) ? utf8_decode($json[$i]['subtitle']) : '';
		$json[$i]['image'] = (isset($json[$i]['image'])) ? $json[$i]['image'] : '';
		$json[$i]['source'] = $page->uri();

		if (!isset($json[$i]['lang'])) {
			$json[$i]['lang'] = 'fr';
		}

		// add to $links
		addArticleToLinks($json[$i], $links, $currentLang);
	}

// else, we are in a simple category
}else {
	$childs = $page->children()->visible();
}

/**
 * get infos of all pages
 */
foreach($childs as $child) {

	$ownLang = $child->content()->language();
	if ($ownLang == '') {
		$ownLang = $site->defaultLanguage()->code();
	}

	if ( $site->language()->code() != $ownLang ) {
		continue;
	}

	if (is_array($child)) {

		$data = $child;

	} else {

		$subtitle = ( isset($child->subtitle()->value) ) ? $child->subtitle()->value : '';
		$image = ( isset($child->image()->value) ) ? $child->image()->value : false;
		if ($image) {
			$image = $child->files->find($image)->url();
		}
		$source = $child->parent()->uri();
		$big = ( isset($child->big()->value) ) ? $child->big()->value : 0;


		$data = array(
			'title' => utf8_decode($child->title()->value),
			'subtitle' => utf8_decode($subtitle),
			'url' => $child->url(),
			'date' => $child->date('Y-m-d H:i:s'),
			'source' => $source,
			'image' => $image,
			'big' => $big,
			'lang' => $currentLang
		);

	}

	addArticleToLinks($data, $links, $currentLang);

}

/**
 * All items are here, just sort them by date
 */
krsort($links);

 ?>

<section class="main" role="main">
	<ul class="wrap Greeed">
	<?php 
	$mois = array('janv', 'févr', 'mars', 'avril', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc');
	$today = new DateTime();
	foreach($links as $content){
		$date = $content['date'];
		$time = new DateTime($date);
		$time = $time->getTimestamp();
		$delta = time() - $time;
		// do not display future article
		if ($delta <= 0) {
			continue;
		}

		$element = 'item';
		$title = utf8_encode($content['title']);
		$subtitle = utf8_encode($content['subtitle']);
		$url = $content['url'];
		$source = strtolower($content['source']);
		$media = $content['image'];
		$big = $content['big'];

		// styles
		$styleBgImage = ($media) ? 'background-image: url(' . $media . ')' : '';
		
		// classes
		$classModifier = $element . '--' . $source;
		$classImg = ($media) ? $element . '--media' : '';
		$classBig = ($big) ? $element . '--big' : '';

		// is new ?
		$new = ( $delta < 60*60*24*20 );

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
					echo setDate($time, $currentLang);
					if ($new) {
						?>
						<span class="<?php echo $element . '-date-new'; ?>" style="font: normal .75em sans-serif;
	color: #FFF;
	background: #f03d36;
	margin-left: 1em;
	padding: .1em .5em;
	border-radius: 2px;"><?php echo l::get('article.new') ?></span>
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