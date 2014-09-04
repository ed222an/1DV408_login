<?php

/**
 * Class route
 * byggt med hjälp av: https://www.youtube.com/watch?v=6reEBParHzQ
 */
class Route{

	/**
	 * @var array
	 */
	private $uri = [];
	/**
	 * @var array
	 */
	private $class_method = [];

	/**
	 * @param $uri
	 * @param $class_method
	 */
	public function add($uri, $class_method){
		$this->uri[] = trim($uri, '/');
		$this->class_method[] = $class_method;
	}

	/**
	 *
	 */
	public function submit(){
		$uri = isset($_REQUEST['uri']) ?$_REQUEST['uri'] : '/';

		if($uri == 'index.php'){
			$uri = '';
		}

		foreach($this->uri as $key => $value){

			if(preg_match("#^$value$#", $uri)) {
				$class_method = explode('@', $this->class_method[$key]);

				$class = $class_method[0];
				$method = $class_method[1];

				$class = new $class();
				$class->$method();
			}
		}

	}

}

