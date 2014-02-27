<?php 

function unwrap ($text) {

	$unwrap_text = substr($text, 3, strlen($text) - 8);

	return $unwrap_text;
}

 ?>