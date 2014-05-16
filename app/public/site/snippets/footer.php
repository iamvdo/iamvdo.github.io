<footer id="footer" <?php echo (isset($article)) ? 'class="articleRelated"' : ''; ?>>
	<div class="wrap">
		<div class="me item item--big item--me">
			<img class="item-media" src="<?php echo url("images/me.jpg"); ?>" alt="Photo d'identité de moi">
			<h2>Vincent De Oliveira <small>(aka iamvdo)</small></h2>
			<p>Salut! Passionné du web, je suis formateur web à l'<a href="http://ensg.eu">ENSG Géomatique</a>, co-auteur du <a href="http://livre-css3.fr">livre «CSS3 Le design web moderne»</a>, créateur de <a href="http://css3create.com">CSS3Create</a> et de <a href="http://pleeease.io">Pleeease</a>.</p><p>J'écris sur mon <a href="<?php echo url('blog') ?>">mon blog</a> et <a href="<?php echo url('ailleurs') ?>">ailleurs</a> et anime même quelques <a href="<?php echo url('conf') ?>">conférences</a>!</p>
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
			<p class="u-small">Fait main avec <span class="u-small">♥</span>, <a href="https://github.com/iamvdo/Greeed">Greeed</a>, <a href="https://github.com/iamvdo/Heeere">Heeere</a> et <a href="http://getkirby.com">Kirby</a>. <?php echo Date('Y'); ?></p>
		</div>
	</div>
</footer>
