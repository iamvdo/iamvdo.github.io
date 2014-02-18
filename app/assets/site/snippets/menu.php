<nav class="menu u-left" role="navigation">
	<ul>
		<li class="menu-item"><a href="<?php echo $site->url(); ?>">Accueil</a></li>
		<?php 
		foreach($pages->visible() AS $p) { 
			?>
			<li class="menu-item">
				<a<?php echo ($p->isOpen()) ? ' class="active"' : '' ?> href="<?php echo $p->url() ?>"><?php echo html($p->title()) ?></a>
			</li>
		<?php 
		}
		?>
		<li class="menu-item"><a href="<?php echo url('rss'); ?>">RSS</a></li>
	</ul>
</nav>