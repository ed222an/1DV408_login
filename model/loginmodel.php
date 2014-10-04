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
	private $sessionUserAgent;

	public function __construct($userAgent)
	{	
		$this->file = new File();
		
		// Sparar användarens useragent i den privata variablerna.
		$this->sessionUserAgent = $userAgent;
		
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
	
	// Kontrollerar loginstatusen. Är användaren inloggad returnerar metoden true, annars false.
	public function checkLoginStatus()
	{
		if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true && $_SESSION['sessionUserAgent'] === $this->sessionUserAgent)
		{
			return TRUE;
		}
		
		return FALSE;
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
		
		if(isset($_SESSION[$this->sessioncookie]) || $this->checkLoginStatus())
		{
			unset($_SESSION[$this->sessioncookie]);
			session_unset();
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
		if($this->checkLoginStatus())
		{
			return TRUE;
		}
		
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
					// Sparar information om den nyvarande användaren.
					$this->currentUser = $inputUsername;
					$this->currentPassword = $inputPassword;
					
					// Sparar information i sessionen.
					$_SESSION['loggedInUser'] = $this->currentUser;
					$_SESSION['loggedIn'] = true;
					$_SESSION['sessionUserAgent'] = $this->sessionUserAgent;
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
}