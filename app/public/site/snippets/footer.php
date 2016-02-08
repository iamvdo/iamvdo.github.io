<footer id="footer" <?php echo (isset($article)) ? 'class="articleRelated"' : ''; ?>>
	<div class="wrap">
		<div class="me item item--big item--me">
			<img class="item-media" src="<?php echo url("images/me.jpg"); ?>" alt="Photo d'identité de moi">
			<h2>Vincent De Oliveira <small>(aka iamvdo)</small></h2>
			<p><?php echo unwrap(markdown($site->footer())); ?></p>
		</div>
		<?php 
		snippet('menubar');
		 ?>
		<div class="underfooter superlink u-align--center">
			<p class="u-small"><?php echo unwrap(markdown($site->mentions())) . ' 2014 - ' . Date('Y'); ?></p>
		</div>
	</div>
</footer>
