<?php 
if (isset($article)) {
	?>
	<script src="<?php echo url('js/vendor.js'); ?>"></script>
	<?php
}
?>
<script src="<?php echo url('js/app.js'); ?>"></script>
<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-17376769-7']);
	_gaq.push(['_setDomainName', 'iamvdo.me']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();

</script>