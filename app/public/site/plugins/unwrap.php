<?php 

function unwrap ($text) {
  $unwrap_text = substr($text, 3);
  $unwrap_text = substr($unwrap_text, 0, -4);
  return $unwrap_text;
}

 ?>