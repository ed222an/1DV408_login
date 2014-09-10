<?php

require_once('./controller/file.php');

class LoginModel{
	/**
	 * @var string
	 */
	private $username = 'Admin';
	/**
	 * @var string
	 */
	private $password = 'Password';

	private $sessioncookie = 'login';
	/**
	 * @var File
	 */
	private $file;

	public function __construct(){
		$this->file = new File();
		$this->file->createFile();
		session_start();
	}

	/**
	 *
	 */
	public function setCookie(){
		$time = strtotime('+2 minutes', strtotime(date('Y-m-d H:i:s')));
		$onePass = md5('');
		$CookieString =  $onePass.'/'.$time;
		$this->file->checkrows();
		$this->file->write($CookieString);
		setcookie($this->sessioncookie, $CookieString, $time);
	}

	/**
	 *
	 */
	public function setSession(){
		$_SESSION[$this->sessioncookie] = $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT']);
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
	public function hasSession(){
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
			$this->messageBox->set('Du har nu loggat ut');
		}
		if(isset($_COOKIE[$this->sessioncookie])) {
			unset($_COOKIE[$this->sessioncookie]);
			setcookie($this->sessioncookie, '', time() - 3600);
		}
	}

	public function hasCookie($messageBox){
		if(isset($_COOKIE[$this->sessioncookie])){
			if($this->file->cookieIsOk($_COOKIE['login'])){
				if(!isset($_SESSION[$this->sessioncookie])) {
					$messageBox->set('Inloggning lyckades via cookies');
				}
				return true;
			}
			unset($_COOKIE[$this->sessioncookie]);
			setcookie($this->sessioncookie, '', time() - 3600);
			$messageBox->set('Felaktig information i cookie');
		}
		return false;
	}
}