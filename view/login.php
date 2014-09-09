<?php
require_once('view/view.php');
require_once('controller/login.php');

/**
 * Class loginView
 */
class loginView extends View{

	/**
	 * @var Login
	 */
	private $Controller;

	/**
	 *
	 */
	public function __construct(){
		$this->Controller = new Login();
	}

	/**
	 *
	 */
	public function index(){
		$inputUsername = '';
		if(isset($_POST['username'])){
			$inputUsername = $_POST['username'];
		}
		if($this->Controller->isLoggedIn()){
			header('Location: ./LoggedIn');
		}
		$this->header('Laborationskod ds222hz');
		?>
			<form action="./LoggedIn" METHOD="post">
				<fieldset>
					<legend>Login - skriv in användarnamn och lösenord</legend>
					<?php echo '<p>' . $this->Controller->getMessage() . '</p>'; ?>
					<label for="username">Användarnamn:</label>
					<input type="text" name="username" id="username" value="<?php echo $inputUsername; ?>"/>
					<label for="password">Lösenord:</label>
					<input type="password" name="password" id="password"/>
					<label for="cookie">Håll mig inloggad:</label>
					<input type="checkbox" id="cookie" name="cookie" value="yes"/>
					<input type="submit" value="Logga in"/>
				</fieldset>
			</form>
		<?php
		$this->footer();
	}

	/**
	 *
	 */
	public function loggedIn(){
		if(!$this->Controller->isLoggedIn()){
			header('Location: ./');
		}
		$this->header('Laborationskod ds222hz');
		?>
			<h2>Admin är inloggad</h2>
			<p><?php echo $this->Controller->getMessage(); ?></p>
			<a href="./?logout">Logga ut</a>
		<?php
		$this->footer();
	}
}