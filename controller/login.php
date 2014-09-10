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
			$this->logout();
		}
	}

	/**
	 *
	 */
	public function getMessage(){
		return $this->messageBox->get();
	}

	/**
	 *
	 */
	public function isLoggedIn(){
		if(!$this->model->hasCookie($this->messageBox) && !$this->model->hasSession()){
			if(isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
				if($_POST['username'] == $this->username && $this->password == $_POST['password']) {
					$this->model->setSession();
					$this->messageBox->set('Inloggning lyckades');
					if (isset($_POST['cookie'])) {
						$this->model->setCookie();
						$this->messageBox->set('Inloggning lyckades och vi kommer ihåg dig nästa gång');
					}
					return true;
				}else{
					$this->messageBox->set('Felaktigt användarnamn och/eller lösenord');
				}
			}else{
				if(isset($_POST['username']) && $_POST['username'] == '') {
					$this->messageBox->set('Användarnamn saknas');
				}else {
					if (isset($_POST['password']) && $_POST['password'] == '') {
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