<?php 

kirbytext::$tags['codepen'] = array(
  'attr' => array(
    'user', 'username', 'script', 'height', 'tabs', 'preview'
  ),
  'html' => function($tag) {

    $hash = $tag->attr('codepen');
    $user = $tag->attr('user', 'iamvdo');
    $username = $tag->attr('username', 'Vincent De Oliveira');
    $height = $tag->attr('height');
    $script = $tag->attr('script');
    $tabs = $tag->attr('tabs', 'result');
    $preview = $tag->attr('preview', 'false');

    $html = '<div class="codepen-placeholder" style="height:' . $height . 'px">';

    $html .= '<p data-height="' . $height . '" data-theme-id="0" data-slug-hash="' . $hash . '" data-user="' . $user . '" data-default-tab="' . $tabs . '" data-preview="' . $preview . '" class="codepen">See the Pen <a href="http://codepen.io/' . $user . '/pen/' . $hash . '">' . $hash . '</a> by ' . $user . ' (<a href="http://codepen.io/' . $user . '">@' . $user . '</a>) on <a href="http://codepen.io">CodePen</a></p>';

    $html .= '</div>';
    // Inserting a script tag creates a bug with markdown
    // $html .= (isset($script)) ? '<script async src="//assets.codepen.io/assets/embed/ei.js"></script>' : '';

    return $html;
  }
);

 ?>