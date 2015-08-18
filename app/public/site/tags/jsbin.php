<?php 

kirbytext::$tags['jsbin'] = array(
  'attr' => array(
    'height', 'tabs'
  ),
  'html' => function($tag) {

    $hash = $tag->attr('jsbin');
    $height = $tag->attr('height');
    $tabs = $tag->attr('tabs', 'output');
    $url = 'http://jsbin.com/' . $hash . '/embed?' . $tabs;

    $html = '<iframe src="' . $url . '" style="width:100%;height:' . $height . 'px" frameborder="0"></iframe>';

    return $html;
  }
);

 ?>