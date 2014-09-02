<?php

class Login{

	private $username = 'Admin';
	private $password = 'Password';
	private $inputUsername;
	private $isLoggedIn = false;
	private $error;

	public function __construct(){
		$this->isLoggedIn();
		if(isset($_GET['logout'])){
			$this->logout();
		}
	}

	private function setCookie(){
		$_COOKIE['login'] = $this->secureStorage($this->username.$this->password);
	}

	private function hasCookie(){
		if(isset($_COOKIE['login'])){
			if($_COOKIE['login'] == $this->secureStorage($this->username.$this->password)){
				return true;
			}
			unset($_COOKIE['login']);
		}
		return false;
	}

	private function setSession(){
		$_SESSION['login'] = $this->secureStorage($this->username.$this->password);
	}

	private function secureStorage($input){
		return md5($input);
	}

	private function hasSession(){
		if(isset($_SESSION['login'])){
			if($_SESSION['login'] == $this->secureStorage($this->username.$this->password)){
				return true;
			}
			unset($_SESSION['login']);
		}
		return false;
	}

	private function logout(){
		if(isset($_SESSION['login'])) {
			unset($_SESSION['login']);
		}
		if(isset($_COOKIE['login'])) {
			unset($_COOKIE['login']);
		}
	}

	private function isLoggedIn(){
		if(!$this->hasCookie() || !$this->hasSession()){
			if(isset($_POST['username']) && isset($_POST['password'])) {
				$this->inputUsername = $_POST['username'];
				if($_POST['username'] == $this->username && $this->password == $_POST['password']) {
					$this->setSession();
					$this->isLoggedIn = true;
					if (isset($_POST['cookie'])) {
						$this->setCookie();
					}
				}else{

				}
			}else{

			}
		}
		$this->isLoggedIn = true;
	}

	public function renderHtml(){
		echo $this->error;
		?>
			<h2>test</h2>
			<form action="./" METHOD="post">
				<label for="username">Username:</label>
				<input type="text" name="username" id="username" value="<?php echo $this->inputUsername; ?>"/>
				<label for="password">Password:</label>
				<input type="password" name="password" id="password"/>
				<label for="cookie">Kom ihåg mig:</label>
				<input type="checkbox" id="cookie" name="cookie" value="yes"/>
				<input type="submit" value="Logga in"/>
			</form>
		<?php
	}

}