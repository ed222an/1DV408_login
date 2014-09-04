<?php

require_once('controller/file.php');

/**
 * Class Login
 */
class Login{

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
	public $message = '';

	/**
	 * @var File
	 */
	private $file;

	/**
	 *
	 */
	public function __construct(){
		$this->file = new File();
		$this->file->createFile();
		session_start();
		if(isset($_GET['logout'])){
			$this->logout();
		}
	}

	/**
	 *
	 */
	private function setCookie(){
		$time = strtotime('+2 minutes', strtotime(date('Y-m-d H:i:s')));
		$onePass = md5('');
		$CookieString =  $onePass.'/'.$time;
		$this->file->checkrows();
		$this->file->write($CookieString);
		setcookie('login', $CookieString, $time);
	}

	/**
	 * @return bool
	 */
	private function hasCookie(){
		if(isset($_COOKIE['login'])){
			if($this->file->cookieIsOk($_COOKIE['login'])){
				if(!isset($_SESSION['login'])) {
					$this->message = 'Inloggning lyckades via cookies';
				}
				return true;
			}
			unset($_COOKIE['login']);
			setcookie('login', '', time() - 3600);
			$this->message = 'Felaktig information i cookie';
		}
		return false;
	}

	/**
	 *
	 */
	private function setSession(){
		$_SESSION['login'] = $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT']);
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
	private function hasSession(){
		if(isset($_SESSION['login'])){
			if($_SESSION['login'] == $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT'])){
				return true;
			}
			unset($_SESSION['login']);
		}
		return false;
	}

	/**
	 *
	 */
	private function logout(){
		if(isset($_SESSION['login'])) {
			unset($_SESSION['login']);
			session_destroy();
			$this->message = 'Du har nu loggat ut';
		}
		if(isset($_COOKIE['login'])) {
			unset($_COOKIE['login']);
			setcookie('login', '', time() - 3600);
		}
	}

	/**
	 *
	 */
	public function isLoggedIn(){
		if(!$this->hasCookie() && !$this->hasSession()){
			if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
				if($_POST['username'] == $this->username && $this->password == $_POST['password']) {
					$this->setSession();
					$this->message = 'Inloggning lyckades';
					if (isset($_POST['cookie'])) {
						$this->setCookie();
						$this->message = 'Inloggning lyckades och vi kommer ihåg dig nästa gång';
					}
					return true;
				}else{
					$this->message = 'Felaktigt användarnamn och/eller lösenord';
				}
			}else{
				if(isset($_POST['username']) && $_POST['username'] == '') {
					$this->message = 'Användarnamn saknas';
				}else {
					if (isset($_POST['password']) && $_POST['password'] == '') {
						$this->message = 'Lösenord saknas';
					}
				}

			}
		}else{
			return true;
		}
		return false;
	}

}