<?php

require_once('./controller/file.php');

class LoginModel
{
	public $sessioncookie = 'davidlogin';
	private $file;
	private $registryFile;
	private $filename = 'userRegistry.txt';
	private $currentUser;
	private $currentPassword;

	public function __construct(){
		$this->file = new File();
		
		// Finns registret sparas det ner i registryFile-variabeln.
		if(file_exists($this->filename))
		{
			$this->registryFile = file_get_contents($this->filename);
		}
	}
	
	public function setCookie($time){
		$onePass = md5('');
		$CookieString =  $onePass.'/'.$time;
		$this->file->checkrows();
		$this->file->write($CookieString);
		return $CookieString;
	}

	public function setSession(){
		$_SESSION[$this->sessioncookie] = $this->secureStorage($this->currentUser.$this->currentPassword.$_SERVER['HTTP_USER_AGENT']);
	}

	public function sessionIsset(){
		return isset($_SESSION[$this->sessioncookie]);
	}
	
	// Hämtar användarnamnet från sessionen.
	public function getLoggedInUser()
	{
		if(isset($_SESSION['loggedInUser']))
		{
			return $_SESSION['loggedInUser'];
		}
	}

	private function secureStorage($input){
		return md5($input);
	}

	public function isLoggedIn(){
		if(isset($_SESSION[$this->sessioncookie])){
			if($_SESSION[$this->sessioncookie] == $this->secureStorage($this->currentUser.$this->currentPassword.$_SERVER['HTTP_USER_AGENT'])){
				return true;
			}
			unset($_SESSION[$this->sessioncookie]);
		}
		return false;
	}

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
	public function login($username, $password)
	{	
		if($this->compareInputWithFile($username, $password))
	 	{
			$this->setSession();
			return TRUE;
		}
		
		return FALSE;
	}

	/**
	 * @param $cookie
	 * @return bool
	 */
	public function cookieIsOk($cookie){
		return $this->file->cookieIsOk($cookie);
	}
	
	// Jämför användarinput med medlemmarna från filen.
	private function compareInputWithFile($inputUsername, $inputPassword)
	{		
		$result = explode(PHP_EOL, $this->registryFile);
			
		foreach($result as $users)
		{
			// Bryter ut användarnamn och lösenord vid semikolon.
			$user = explode(";", $users);
			
			// Kontrollerar ifall användarnamnet är detsamma som det nya användarnamnet.
			if($user[0] == $inputUsername)
			{
				if(isset($user[1]) && $user[1] == $inputPassword)
				{
					$this->currentUser = $inputUsername;
					$this->currentPassword = $inputPassword;
					$_SESSION['loggedInUser'] = $this->currentUser;
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
}