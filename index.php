<?php
require_once('./route.php');
require_once('view/login.php');

$route = New Route();

$route->add('/', 'loginView@index');
$route->add('/LoggedIn', 'loginView@loggedIn');

$route->submit();
