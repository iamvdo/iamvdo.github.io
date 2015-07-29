<header>
	<?php 
	include('images/svg.svg');
	 ?>
	<div class="wrap">
		<p class="name">
			<a href="<?php echo url('/'); ?>">
				<span class="text"><strong>Vincent</strong> De Oliveira</span>
				<span class="retour"><?php echo l::get('backHome'); ?></span>
			</a>
		</p>
		<nav class="menu u-right" role="navigation">
			<a class="menu-item icon icon--blog" href="<?php echo url('blog'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50" height="50" class="svg-icon svg-icon--blog" role="img" aria-label="Blog">
					<clipPath id="clip-blog">
						<use xlink:href="#svg-blog"></use>
					</clipPath>
					<g clip-path="url(#clip-blog)">
						<rect class="svg-icon-color" width="120" height="120" x="-10" y="-10"></rect>
						<rect class="svg-icon-mask" width="120" height="120" x="-10" y="-10" transform="translate(0,100)"></rect>
					</g>
				</svg>
			</a>
			<a class="menu-item icon icon--conf" href="<?php echo url('conf'); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50" height="50" class="svg-icon svg-icon--conf" role="img" aria-label="Conférences">
					<clipPath id="clip-conf">
						<use xlink:href="#svg-conf"></use>
					</clipPath>
					<g clip-path="url(#clip-conf)">
						<rect class="svg-icon-color" width="120" height="120" x="-10" y="-10"></rect>
						<rect class="svg-icon-mask" width="120" height="120" x="-10" y="-10" transform="translate(0,100)"></rect>
					</g>
				</svg>
			</a>
			<a class="menu-item icon icon--twitter" href="http://twitter.com/iamvdo">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="50" height="50" class="svg-icon svg-icon--twitter" role="img" aria-label="Twitter">
					<clipPath id="clip-twitter">
						<use xlink:href="#svg-twitter"></use>
					</clipPath>
					<g clip-path="url(#clip-twitter)">
						<rect class="svg-icon-color" width="120" height="120" x="-10" y="-10"></rect>
						<rect class="svg-icon-mask" width="120" height="120" x="-10" y="-10" transform="translate(0,100)"></rect>
					</g>
				</svg>
			</a>
			<div class="menu-item lang">
				<?php 
				foreach ($site->languages() as $lang) {
					if ($site->language() == $lang) {
						?>
						<span class="lang-item">
							<?php echo $lang->code(); ?>
						</span>
					<?php
					} else {
						?>
						<a class="lang-item" href="<?php echo $lang->url() ?>">
							<?php echo $lang->code(); ?>
						</a>
					<?php
					}
				}
				 ?>
			</div>
		</nav>
	</div>
</header>