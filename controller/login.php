<?php
require_once('./model/loginmodel.php');
require_once('./view/login.php');
require_once('./view/registerView.php');

class Login{

	private $loginModel;
	private $loginView;
	private $registerView;
	private $userHasRegistered = FALSE;

	public function __construct(){
			
		// Sparar ner användarens användaragent och ip. Används vid verifiering av användaren.
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		
		$this->loginModel = new LoginModel($userAgent);
		$this->loginView = new loginView($this->loginModel);
		$this->registerView = new RegisterView();
	}

	public function dotoggle(){
		
		// Har användaren klickat på registrerarknappen, eller om "register" är med i url-en visas registreringssidan.
		if($this->loginView->getRegister() && $this->userHasRegistered === FALSE)
		{
			// Validera användarens input.
			if($this->registerView->validateUserInput())
			{
				// Spara den nya användaren.
				$this->registerView->saveNewUser();
				
				// Användare har registrerats, visa inloggningssidan.
				$this->userHasRegistered = TRUE;
				$this->dotoggle();
			}
		}
		else
		{
			// Visar den nyregistrerade användaren.
			if($this->userHasRegistered === TRUE)
			{
				$this->loginView->show(FALSE, TRUE);
				$this->userHasRegistered = FALSE;
			}
			else
			{
				// Visa den vanliga inloggningssidan.
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
}