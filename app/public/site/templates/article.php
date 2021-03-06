<?php snippet('doctype') ?>
<?php 
	$title = html($page->title());
	$title .= ' - ' . html($site->author());
	$description = $page->description();
	if ($description == '') {
		$description = excerpt($page->text(), 300);
	}
	?>
	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:url" content="<?php echo thisUrl(); ?>">
	<meta property="og:description" content="<?php echo $description; ?>">
	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $description; ?>">
</head>
<body>
	<?php 
	snippet('header');
	snippet('intro');
	?>
	<div class="wrap wrap--narrow">
		<div class="underfooter superlink">
			<?php
			snippet('menu');
			?>
		</div>
	</div>
	<section class="main article" role="main">
		<?php 
		snippet('aside-blog');
		 ?>
		<div class="wrap">
			<div class="item-media">
				<?php 
				$source = $page->parent()->uri();
				 ?>
				<svg viewBox="0 0 100 100" width="50" height="50" class="svg-icon svg-icon--<?php echo $source; ?>">
					<use xlink:href="#svg-<?php echo $source; ?>">
				</svg>
			</div>
			<div class="article-utils">
				<a class="utils-link" href="<?php echo url($source); ?>"><?php echo $page->parent->title(); ?></a>
				<p class="u-right"><?php echo l::get('article.readingTime'); ?>&nbsp;: <?php echo ceil(str_word_count(kirbytext($page->text())) / 250); ?>min</p>
			</div>
			<?php
			snippet('langAvailable');
			?>
			<p class="bmac"><?php echo l::get('bmac-text') ?> <a class="bmac-link" href="https://www.buymeacoffee.com/iamvdo"><?php echo l::get('bmac-link') ?></a></p>
			<div class="article-text language-css">
				<?php echo $page->text()->kirbytext(); ?>
			</div>
			<p class="bmac"><?php echo l::get('bmac-text') ?> <a class="bmac-link" href="https://www.buymeacoffee.com/iamvdo"><?php echo l::get('bmac-link') ?></a></p>
			<div class="article-utils article-utils--footer">
				<?php 
				if ($page->hasPrevVisible('date')) {
					$ownLang = $page->prevVisible('date')->content()->language();
					if ($ownLang == '') {
						$ownLang = $site->defaultLanguage()->code();
					}
					if ( $site->language()->code() == $ownLang ) {
					?>
						<a class="utils-link" href="<?php echo $page->prevVisible('date')->url(); ?>"><?php echo $page->prevVisible('date')->title(); ?></a>
						<?php
					}
				}
				if ($page->hasNextVisible('date') and ((time() - $page->nextVisible('date')->date()) > 0)) {
					$ownLang = $page->nextVisible('date')->content()->language();
					if ($ownLang == '') {
						$ownLang = $site->defaultLanguage()->code();
					}
					if ( $site->language()->code() == $ownLang ) {
						?>
						<a class="utils-link utils-link--next u-right" href="<?php echo $page->nextVisible('date')->url(); ?>"><?php echo $page->nextVisible('date')->title(); ?></a>
						<?php
					}
				}
				?>
			</div>
		</div>

		<div id="disqus_thread" class="wrap">
			<?php
			$count = '';
			$msg = l::get('comments.write');
			$rq = 'http://iamvdo.disqus.com/count-data.js?2=http://' . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') . '&nocache=' . rand() . rand();
			$req = curl_init($rq);
			curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($req, CURLOPT_FOLLOWLOCATION, true);
			if ($str = curl_exec($req)) {
				$needle = '"comments":';
				$i = strrpos($str, $needle);
				// if comments
				if ( $i !== 0) {
					// get the count
					$count = substr($str, $i + strlen($needle), -7);
					if ($count != 0) {
						$msg = l::get('comments.load') . '<span> (' . $count . ')</span>';
					}
				} else {
					// else, we don't know how many comments
					$msg = l::get('comments.load');
				}
			}
			?>
			<button class="u-align--center button button--large"><?php echo $msg; ?></button>
		</div>
		<script type="text/javascript">
			var disqus_shortname  = 'iamvdo';
			var disqus_url = [location.protocol, '//', location.host, location.pathname].join('');
			(function() {
				var disqus_thread = document.getElementById('disqus_thread');
				function loadComments() {
					console.log('load');
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					dsq.onload = removeClick();
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				}
				function removeClick () {
					console.log('remove');
					disqus_thread.removeEventListener('click', loadComments);
				}
				disqus_thread.addEventListener('click', loadComments);
			})();
		</script>

	</section>

	<?php
	$article = array('article' => $page->parent());
	snippet('footer', $article);
	snippet('script', $article);
	?>
	
</body>
</html>