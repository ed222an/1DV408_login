<?php
session_start();
require_once('login.php');

$Login = new Login();

$Login->renderHtml();