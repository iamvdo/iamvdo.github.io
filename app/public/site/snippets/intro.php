<?php 
/**
 * dir is blog, conf, tagged or home
 */
$dir = $page->uri;
$title = $page->title();
$subtitle = $page->subtitle();
// tag case
if ($dir === 'tagged') {

	foreach ($_GET as $key => $value) {
		$title .= ' ' . $key;
	}
	
}

 ?>
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