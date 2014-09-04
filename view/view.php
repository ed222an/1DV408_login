<?php

/**
 * Class View
 */
class View{

	/**
	 * @param string $title
	 */
	public function header($title = ''){
		?>
			<!doctype html>
			<html>
			<head>
				<meta charset="utf-8">
				<meta name="description" content="">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title><?php echo $title; ?></title>
			</head>
			<body>
			<h1>Laborationskod ds222hz</h1>
		<?php
	}

	/**
	 *
	 */
	public function footer(){
		setlocale(LC_ALL, 'swedish');
		echo '<p>'.ucfirst(strftime('%A')).', den '.date('j ').ucfirst(strftime('%B')).' år '.date('Y').'. Klockan är ['.date('H:i:s').']</p>';
		?>
			</body>
			</html>
		<?php
	}

}