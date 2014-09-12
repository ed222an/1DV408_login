<?php

class MessageBox{

	private $key = "message";

	public function set($value){
		setcookie( $this->key, $value, 3600);
		$_COOKIE[$this->key] = $value;
	}

	public function get(){
		if (isset($_COOKIE[$this->key])) {
			$message = $_COOKIE[$this->key];
		}else {
			$message = "";
		}

		setcookie($this->key, "", time() -1);
		unset($_COOKIE[$this->key]);

		return $message;
	}

}