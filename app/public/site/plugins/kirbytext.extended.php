<?php

class kirbytextExtended extends kirbytext {

	function __construct($text, $markdown=true) {

		parent::__construct($text, $markdown);

		// define custom tags
		$this->addTags('codepen');

		// define custom attributes
		$this->addAttributes('user');
		$this->addAttributes('username');
		$this->addAttributes('script');

	}


	function codepen($params) {

		// define default values for attributes
		$defaults = array(
			'user'     => 'iamvdo',
			'username' => 'Vincent De Oliveira'
		);

		// merge the given parameters with the default values
		$options = array_merge($defaults, $params);

		$hash = $options['codepen'];
		$user = $options['user'];
		$username = $options['username'];
		$height = $options['height'];
		$script = (isset($options['script']));

		$html = '<p data-height="' . $height . '" data-theme-id="0" data-slug-hash="' . $hash . '" data-user="' . $user . '" data-default-tab="result" class="codepen">See the Pen <a href="http://codepen.io/' . $user . '/pen/' . $hash . '">' . $hash . '</a> by ' . $user . ' (<a href="http://codepen.io/' . $user . '">@' . $user . '</a>) on <a href="http://codepen.io">CodePen</a></p>';

		$html .= ($script) ? '<script async src="//codepen.io/assets/embed/ei.js"></script>' : '';

		// return the HTML
		return $html;

	}

}

?>