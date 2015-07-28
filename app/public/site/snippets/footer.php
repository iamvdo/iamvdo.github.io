<footer id="footer" <?php echo (isset($article)) ? 'class="articleRelated"' : ''; ?>>
	<div class="wrap">
		<div class="me item item--big item--me">
			<img class="item-media" src="<?php echo url("images/me.jpg"); ?>" alt="Photo d'identité de moi">
			<h2>Vincent De Oliveira <small>(aka iamvdo)</small></h2>
			<p><?php echo unwrap(markdown($site->footer())); ?></p>
		</div>
		<div class="underfooter superlink">
			<?php snippet('menu'); ?>
			<nav class="menu u-right">
				<ul>
					<li class="menu-item"><a href="http://twitter.com/iamvdo">Twitter</a></li>
					<li class="menu-item"><a href="https://plus.google.com/u/0/109110310055687711031/posts">Google+</a></li>
					<li class="menu-item"><a href="http://dribbble.com/iamvdo">Dribbble</a></li>
					<li class="menu-item"><a href="http://github.com/iamvdo">GitHub</a></li>
					<li class="menu-item">vincent@iamvdo.me</li>
				</ul>
			</nav>
		</div>
		<div class="underfooter superlink u-align--center">
			<p class="u-small"><?php echo unwrap(markdown($site->mentions())) . ' 2014 - ' . Date('Y'); ?></p>
		</div>
	</div>
</footer>
