<?php 
snippet('doctype');
$title = '';
// if it's not the homepage (so only categories)
if (!$page->isHomePage()) {
	$title .= html($page->title()) . ' - ';
}
$title .= html($site->title());
?>
	<meta property="og:title" content="<?php echo $title; ?>">
	<meta property="og:url" content="<?php echo thisUrl(); ?>">
	<meta property="og:description" content="<?php echo $site->description(); ?>">
	<title><?php echo $title; ?></title>
	<meta name="description" content="<?php echo $site->description(); ?>">
</head>
<body>
	<?php 
	snippet('header');
	snippet('intro');
	?>
	<div class="wrap">
		<div class="underfooter superlink">
			<?php
			snippet('menu');
			?>
		</div>
	</div>
	<?php
	snippet('main');
	snippet('footer');
	snippet('script');
	 ?>
</body>
</html>