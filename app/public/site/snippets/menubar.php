<div class="underfooter superlink">
  <?php snippet('menu'); ?>
  <nav class="menu u-right">
    <ul>
      <li class="menu-item"><a href="http://twitter.com/iamvdo">Twitter</a></li>
      <li class="menu-item"><a href="http://dribbble.com/iamvdo">Dribbble</a></li>
      <li class="menu-item"><a href="http://github.com/iamvdo">GitHub</a></li>
      <li class="menu-item"><a href="<?php echo $site->full_url() . $site->homePage()->url(); ?>/rss">RSS</a></li>
      <li class="menu-item" id="myemail">...</li>
      <script>
        let a = 'vincent';
        let b = 'iamvdo';
        myemail.innerHTML = a + '@' + b + '.me';
      </script>
    </ul>
  </nav>
</div>