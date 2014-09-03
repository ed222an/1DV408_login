<?php

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
	 * @var
	 */
	private $inputUsername;
	/**
	 * @var bool
	 */
	private $isLoggedIn = false;
	/**
	 * @var string
	 */
	private $message = '';

	/**
	 *
	 */
	public function __construct(){
		session_start();
		if(isset($_GET['logout'])){
			$this->logout();
		}
		$this->isLoggedIn();
	}

	/**
	 *
	 */
	private function setCookie(){
		setcookie('login', $this->secureStorage($this->username.$this->password));
	}

	/**
	 * @return bool
	 */
	private function hasCookie(){
		if(isset($_COOKIE['login'])){
			if($_COOKIE['login'] == $this->secureStorage($this->username.$this->password)){
				$this->message = 'Inloggning lyckades via cookies';
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
	private function isLoggedIn(){
		if(!$this->hasCookie() && !$this->hasSession()){
			if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
				$this->inputUsername = $_POST['username'];
				if($_POST['username'] == $this->username && $this->password == $_POST['password']) {
					$this->setSession();
					$this->isLoggedIn = true;
					$this->message = 'Inloggning lyckades';
					if (isset($_POST['cookie'])) {
						$this->setCookie();
						$this->message = 'Inloggning lyckades och vi kommer ihåg dig nästa gång';
					}
				}else{
					$this->message = 'Felaktigt användarnamn och/eller lösenord';
				}
			}else{
				if(isset($_POST['username']) && $_POST['username'] == '') {
					$this->message = 'Användarnamn saknas';
				}
				if(isset($_POST['password']) && $_POST['password'] == '') {
					$this->message = 'Lösenord saknas';
				}

			}
		}else{
			$this->isLoggedIn = true;
		}
	}

	/**
	 *
	 */
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
						<?php echo '<p>' . $this->message . '</p>'; ?>
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
				<p><?php echo $this->message; ?></p>
				<a href="./?logout">Logga ut</a>
			<?php
		}
		?>
				</body>
			</html>
		<?php
	}

}