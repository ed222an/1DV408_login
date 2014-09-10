<?php
require_once('./vendor/MessageBox.php');
require_once('./model/loginmodel.php');

/**
 * Class Login
 */
class Login{

	/**
	 * @var MessageBox
	 */
	private $messageBox;

	private $model;

	/**
	 *
	 */
	public function __construct(){
		$this->messageBox = new MessageBox();
		$this->model = new LoginModel();
		if(isset($_GET['logout'])){
			$this->messageBox->set($this->model->logout());
		}
	}

	/**
	 *
	 */
	public function getMessage(){
		return $this->messageBox->get();
	}

	private function hasValue($input){
		return isset($input) && $input != '';
	}

	/**
	 *
	 */
	public function isLoggedIn(){
		$username = isset($_POST['username']) ? $_POST['username'] : "";
		$password = isset($_POST['password']) ? $_POST['password'] : "";
		$cookie = isset($_POST['cookie']) ? $_POST['cookie'] : null;

		if(!$this->model->hasCookie($this->messageBox) && !$this->model->hasSession()){
			if($this->hasValue($username) && $this->hasValue($password)) {
				$login = $this->model->login($username, $password, $cookie);
				if($login) {
					$this->messageBox->set($login);
					return true;
				}else{
					$this->messageBox->set('Felaktigt användarnamn och/eller lösenord');
				}
			}else{
				if(!$this->hasValue($username)) {
					$this->messageBox->set('Användarnamn saknas');
				}else {
					if (!$this->hasValue($password)) {
						$this->messageBox->set('Lösenord saknas');
					}
				}

			}
		}else{
			return true;
		}
		return false;
	}

}