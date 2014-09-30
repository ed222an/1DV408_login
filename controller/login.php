<?php
require_once('./model/loginmodel.php');
require_once('./view/login.php');
require_once('./view/registerView.php');

class Login{

	private $loginModel;
	private $loginView;
	private $registerView;

	public function __construct(){
		$this->loginModel = new LoginModel();
		$this->loginView = new loginView();
		$this->registerView = new RegisterView();
	}

	public function dotoggle(){
			
		// Har användaren klickat på registrerarknappen, eller om "register" är med i url-en visas registreringssidan.
		if($this->loginView->getRegister())
		{
			$this->registerView->validateUserInput();
		}
		
		// Annars visas den vanliga inloggningssidan.
		else
		{
			$isLoggedin = $this->loginModel->isLoggedIn();
			if(!$isLoggedin){
				$username = $this->loginView->getUsername();
				$password = $this->loginView->getPassword();
				$isLoggedin = $this->loginModel->login($username, $password);
			}
			if($this->loginView->getLogout()){
				$isLoggedin = false;
			}
	
			$this->loginView->show($isLoggedin);
		}
	}
}