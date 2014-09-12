<?php
require_once('view/view.php');
require_once('controller/login.php');
require_once('model/loginmodel.php');
require_once('./vendor/MessageBox.php');

/**
 * Class loginView
 */
class loginView extends View{

	private $model;

	private $messageBox;

	public function __construct(){
		$this->model = new LoginModel();
		$this->messageBox = new MessageBox();
	}

	public function getUsername(){
		$username = isset($_POST['username']) ? $_POST['username'] : "";
		return $username;
	}

	public function getPassword(){
		$password = isset($_POST['password']) ? $_POST['password'] : "";
		return $password;
	}

	public function setCookie(){
		$time = strtotime('+2 minutes', strtotime(date('Y-m-d H:i:s')));
		$content = $this->model->setCookie($time);
		setcookie($this->model->sessioncookie, $content, $time);
		$_COOKIE[$this->model->sessioncookie] = $content;
	}

	public function hasCookie(){
		if(isset($_COOKIE[$this->model->sessioncookie])){
			if($this->model->cookieIsOk($this->model->sessioncookie)){
				if(!isset($_SESSION[$this->model->sessioncookie])) {
					$this->messageBox->set('Inloggning lyckades via cookies');
				}
				return true;
			}
			unset($_COOKIE[$this->sessioncookie]);
			setcookie($this->sessioncookie, '', time() - 3600);
			$this->messageBox->set('Felaktig information i cookie');
		}
		return false;
	}

	private function index($message = ""){
		$this->header('Laborationskod ds222hz');
		?>
			<form action="" METHOD="post">
				<fieldset>
					<legend>Login - skriv in användarnamn och lösenord</legend>
					<?php echo '<p>' . $this->messageBox->get() . '</p>'; ?>
					<label for="username">Användarnamn:</label>
					<input type="text" name="username" id="username" value="<?php echo $this->getUsername(); ?>"/>
					<label for="password">Lösenord:</label>
					<input type="password" name="password" id="password"/>
					<label for="cookie">Håll mig inloggad:</label>
					<input type="checkbox" id="cookie" name="cookie" value="yes"/>
					<input type="submit" name="login" value="Logga in"/>
				</fieldset>
			</form>
		<?php
		$this->footer();
	}

	private function loggedIn($message = "") {
		$this->header('Laborationskod ds222hz');
		?>
			<h2>Admin är inloggad</h2>
			<p><?php echo $this->messageBox->get(); ?></p>
			<a href="./?logout">Logga ut</a>
		<?php
		$this->footer();
	}

	private function deleteCookie(){
		if(isset($_COOKIE[$this->model->sessioncookie])) {
			unset($_COOKIE[$this->model->sessioncookie]);
			setcookie($this->model->sessioncookie, '', time() - 3600);
		}
	}

	public function logout(){
		$this->deleteCookie();
		if($this->model->logout()){
			$this->messageBox->set('Du har nu loggat ut');
		}
	}

	public function show($isLoggedIn){
		if(isset($_GET['logout'])){
			$this->logout();
		}
		if($isLoggedIn || $this->hasCookie()){
			if(isset($_POST['cookie'])) {
				$this->setCookie();
				$this->messageBox->set('Inloggning lyckades och vi kommer ihåg dig nästa gång');
			}else{
				$this->messageBox->set('Inloggning lyckades');
			}
			$this->loggedIn();
		}else{
			if(isset($_POST['login'])){
				if($this->getUsername() == '') {
					$this->messageBox->set('Användarnamn saknas');
				} else if($this->getPassword() == '') {
					$this->messageBox->set('Lösenord saknas');
				}else{
					$this->messageBox->set('Felaktigt användarnamn och/eller lösenord');
				}
			}
			$this->index();
		}
	}
}