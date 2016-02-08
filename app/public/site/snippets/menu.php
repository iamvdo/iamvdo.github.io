<nav class="menu u-left" role="navigation">
	<ul>
		<li class="menu-item"><a href="<?php echo $site->language()->url(); ?>"><?php echo l::get('home'); ?></a></li>
		<?php 
		foreach($pages->find('blog', 'design', 'code') AS $p) { 
			?>
			<li class="menu-item">
				<a<?php echo ($p->isOpen()) ? ' class="active"' : '' ?> href="<?php echo $p->url() ?>"><?php echo html($p->title()) ?></a>
			</li>
		<?php 
		}
		?>
	</ul>
</nav>