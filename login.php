<?php

class Login{

	private $username = 'Admin';
	private $password = 'Password';
	private $inputUsername;
	private $isLoggedIn = false;
	private $error = '';

	public function __construct(){
		if(isset($_GET['logout'])){
			$this->logout();
		}
		$this->isLoggedIn();
	}

	private function setCookie(){
		setcookie('login', $this->secureStorage($this->username.$this->password));
	}

	private function hasCookie(){
		if(isset($_COOKIE['login'])){
			if($_COOKIE['login'] == $this->secureStorage($this->username.$this->password)){
				$this->error = 'Inloggning lyckades via cookies';
				return true;
			}
			unset($_COOKIE['login']);
			setcookie('login', '', time() - 3600);
			$this->error = 'Felaktig information i cookie';
		}
		return false;
	}

	private function setSession(){
		$_SESSION['login'] = $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT']);
	}

	private function secureStorage($input){
		return md5($input);
	}

	private function hasSession(){
		if(isset($_SESSION['login'])){
			if($_SESSION['login'] == $this->secureStorage($this->username.$this->password.$_SERVER['HTTP_USER_AGENT'])){
				return true;
			}
			unset($_SESSION['login']);
		}
		return false;
	}

	private function logout(){
		if(isset($_SESSION['login'])) {
			unset($_SESSION['login']);
			session_destroy();
			$this->error = 'Du har nu loggat ut';
		}
		if(isset($_COOKIE['login'])) {
			unset($_COOKIE['login']);
			setcookie('login', '', time() - 3600);
		}
	}

	private function isLoggedIn(){
		if(!$this->hasCookie() && !$this->hasSession()){
			if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
				$this->inputUsername = $_POST['username'];
				if($_POST['username'] == $this->username && $this->password == $_POST['password']) {
					$this->setSession();
					$this->isLoggedIn = true;
					$this->error = 'Inloggning lyckades';
					if (isset($_POST['cookie'])) {
						$this->setCookie();
						$this->error = 'Inloggning lyckades och vi kommer ihåg dig nästa gång';
					}
				}else{
					$this->error = 'Felaktigt användarnamn och/eller lösenord';
				}
			}else{
				if(isset($_POST['username']) && $_POST['username'] == '') {
					$this->error = 'Användarnamn saknas';
				}
				if(isset($_POST['password']) && $_POST['password'] == '') {
					$this->error = 'Lösenord saknas';
				}

			}
		}else{
			$this->isLoggedIn = true;
		}
	}

	public function renderHtml(){
		?>
			<!doctype html>
			<html>
			<head>
				<meta charset="utf-8">
				<meta name="description" content="">
				<meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Laborationskod ds222hz</title>
			</head>
			<body>
		<?php
		echo '<h1>Laborationskod ds222hz</h1>';
		if(!$this->isLoggedIn) {
			?>
				<h2>Ej Inloggad</h2>
				<form action="./" METHOD="post">
					<fieldset>
						<legend>Login - skriv in användarnamn och lösenord</legend>
						<?php echo '<p>' . $this->error . '</p>'; ?>
						<label for="username">Användarnamn:</label>
						<input type="text" name="username" id="username" value="<?php echo $this->inputUsername; ?>"/>
						<label for="password">Lösenord:</label>
						<input type="password" name="password" id="password"/>
						<label for="cookie">Håll mig inloggad:</label>
						<input type="checkbox" id="cookie" name="cookie" value="yes"/>
						<input type="submit" value="Logga in"/>
					</fieldset>
				</form>
			<?php
		}else{
			?>
				<h2>Admin är inloggad</h2>
				<p><?php echo $this->error; ?></p>
				<a href="./?logout">Logga ut</a>
			<?php
		}
		?>
				</body>
			</html>
		<?php
	}

}