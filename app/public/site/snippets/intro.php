<?php 
/**
 * dir is blog, conf, tagged or home
 */
$dir = $page->uri();
$title = $page->title();
$subtitle = $page->subtitle();
// tag case
if ($dir === 'tagged') {

	foreach ($_GET as $key => $value) {
		$title .= ' ' . $key;
	}
	
}

?>
<!--
<dl class="news superlink">
	<dt class="news-title">INFO</dt>
<?php
if ($site->language()->code() == 'fr') {
	?>
	<dd class="news-text">Blog now available in <a href="<?php echo $site->language('en')->url(); ?>"><?php echo $site->language('en')->name(); ?></a></dd>
	</dl>
	<?php 
} else {
	?>
	<dd class="news-text">Voir le blog en <a href="<?php echo $site->language('fr')->url(); ?>"><?php echo $site->language('fr')->name(); ?></a></dd>
	<?php 
}
?>
</dl>
-->
<section class="intro">
	<div class="intro-content">
		<h1 class="intro-title"><?php echo unwrap(markdown($title)); ?></h1>
		<p class="intro-more superlink">
			<?php 
			$blog = $pages->find('blog');
			if ( $page->isChildOf($blog) ) {

				snippet('article-blog-info');

			} elseif ( $subtitle !== NULL ) {
				
				echo unwrap(markdown($subtitle));

			} else {
				?>
				<a href="#footer">en savoir plus sur moi</a>
				<!-- et aussi un <a href="blog">blog</a>, des <a href="http://css3create.com">tutoriels CSS3</a>, des <a href="conf">conférences</a> -->
			<?php
			}
			?>
		</p>
	</div>
</section>