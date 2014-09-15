<?php
require_once('./model/loginmodel.php');
require_once('./view/login.php');

/**
 * Class Login
 */
class Login{

	/**
	 * @var LoginModel
	 */
	private $model;

	/**
	 * @var loginView
	 */
	private $view;

	/**
	 *
	 */
	public function __construct(){
		$this->model = new LoginModel();
		$this->view = new loginView();
	}

	/**
	 *
	 */
	public function dotoggle(){
		$isLoggedin = $this->model->isLoggedIn();
		if(!$isLoggedin){
			$username = $this->view->getUsername();
			$password = $this->view->getPassword();
			$isLoggedin = $this->model->login($username, $password);
		}
		if($this->view->getLogout()){
			$isLoggedin = false;
		}

		$this->view->show($isLoggedin);
	}

}