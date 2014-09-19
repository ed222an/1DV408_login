<?php

require_once('./controller/file.php');

/**
 * Class LoginModel
 */
class LoginModel{
	/**
	 * @var string
	 */
	private $username = 'Admin';
	/**
	 * @var string
	 */
	private $password = 'Password';

	/**
	 * @var string
	 */
	public $sessioncookie = 'davidlogin';
	/**
	 * @var File
	 */
	private $file;

	/**
	 *
	 */
	public function __construct(){
		$this->file = new File();
	}

	/**
	 *
	 */
	public function setCookie($time){
		$onePass = md5('');
		$CookieString =  $onePass.'/'.$time;
		$this->file->checkrows();
		$this->file->write($CookieString);
		return $CookieString;
	}

	/**
	 *
	 */
	public function setSession(){
		$_SESSION[$this->sessioncookie] = $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT']);
	}

	public function sessionIsset(){
		return isset($_SESSION[$this->sessioncookie]);
	}

	/**
	 * @param $input
	 * @return string
	 */
	private function secureStorage($input){
		return md5($input);
	}

	/**
	 * @return bool
	 */
	public function isLoggedIn(){
		if(isset($_SESSION[$this->sessioncookie])){
			if($_SESSION[$this->sessioncookie] == $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT'])){
				return true;
			}
			unset($_SESSION[$this->sessioncookie]);
		}
		return false;
	}

	/**
	 *
	 */
	public function logout(){
		if(isset($_SESSION[$this->sessioncookie])) {
			unset($_SESSION[$this->sessioncookie]);
			session_destroy();
			return true;
		}
		return false;
	}

	/**
	 * @param $username
	 * @param $password
	 * @return bool
	 */
	public function login($username, $password){
		if($username == $this->username && $this->password == $password) {
			$this->setSession();
			return true;
		}
		return false;
	}

	/**
	 * @param $cookie
	 * @return bool
	 */
	public function cookieIsOk($cookie){
		return $this->file->cookieIsOk($cookie);
	}
}