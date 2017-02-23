<script src="<?php echo url('js/vendor.js'); ?>"></script>
<script src="<?php echo url('js/app.js'); ?>"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-17376769-7', 'auto');
  ga('send', 'pageview');

  document.querySelector('.flattr-link').addEventListener('click', function () {
    ga('send', 'event', 'Flattr', 'Click');
  });
</script>