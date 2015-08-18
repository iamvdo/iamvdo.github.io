<?php 

kirbytext::$tags['code'] = array(
  'attr' => array(
    'type'
  ),
  'html' => function($tag) {

    $code = htmlentities($tag->attr('code'));
    $type = $tag->attr('type', 'markup');

    $html = '<pre class="language-' . $type . '"><code>' . $code . '</code></pre>';

    return $html;
  }
);

 ?>