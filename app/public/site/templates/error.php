<?php 
snippet('doctype');
$title = '';
// if it's not the homepage (so only categories)
if (!$page->isHomePage()) {
	$title .= html($page->title()) . ' - ';
}
$title .= html($site->title());
?>
	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $site->description(); ?>">
</head>
<body>
	<?php 
	snippet('header');
	snippet('intro');
	?>
	<section class="main article">
	<div class="wrap">
		<div class="article-text">
			<?php 
			echo kirbytext($page->text());
			?>
			<ul>
				<?php
				$shuffle = $pages->visible()->children()->shuffle()->limit(3);
				foreach ($shuffle as $page) {
					?>
					<li><a href="<?php echo $page->url(); ?>"><?php echo $page->title(); ?></a></li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>
	</section>
	<?php
	$article = array('article' => $page->parent());
	snippet('footer', $article);
	snippet('script', $article);
	 ?>
</body>
</html>