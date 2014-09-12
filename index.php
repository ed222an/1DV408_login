<?php
session_start();
require_once('controller/login.php');
$view = new Login();
$view->dotoggle();
