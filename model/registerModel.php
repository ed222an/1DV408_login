<?php

class RegisterModel
{
	// Variabler för användarinput.
	private $requestedUsername;
	private $requestedPassword;
	private $repeatedRequestedPassword;
	
	// Arrayer för de lagrade användarnamnen & lösenorden.
	private $storedUsernames = array();
	private $storedPasswords = array();
}
?>