<?php 
kirbytext::$tags['video'] = array(
  'attr' => array(
    'width',
    'height',
    'alt',
    'text',
    'title',
    'class',
    'imgclass',
    'linkclass',
    'caption',
    'link',
    'target',
    'popup',
    'rel'
  ),
  'html' => function($tag) {

    $url     = $tag->attr('video');
    $width   = $tag->attr('width', 300);
    $caption = $tag->attr('caption');
    $file    = $tag->file($url . '.mp4');

    $url = $file ? $file->url() : url($url);

    // remove extension
    $url = str_replace('.mp4', '', $url);

    $video  = '<video autoplay loop muted controls width="' . $width . '">';
    $video .= '<source src="' . $url . '.webm" type="video/webm">';
    $video .= '<source src="' . $url . '.mp4" type="video/mp4">';
    $video .= '</video>';

    $figure = new Brick('figure');
    $figure->append($video);
    if(!empty($caption)) {
      $figure->append('<figcaption class="caption">' . html($caption) . '</figcaption>');
    }
    return $figure;

  }
);
?>