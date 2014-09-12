<?php
require_once('./model/loginmodel.php');
require_once('./view/login.php');

/**
 * Class Login
 */
class Login{

	private $model;

	private $view;

	public function __construct(){
		$this->model = new LoginModel();
		$this->view = new loginView();
	}

	public function dotoggle(){
		$isLoggedin = false;
		if(!$this->model->isLoggedIn()){
			$username = $this->view->getUsername();
			$password = $this->view->getPassword();
			$isLoggedin = $this->model->login($username, $password);
		}

		$this->view->show($isLoggedin);
	}

}