<?php

class RegisterModel
{
	// Arrayer för de lagrade användarnamnen & lösenorden.
	private $storedUsernames = array();
	private $storedPasswords = array();
	
	public function __construct()
	{
		//$this->storedUsernames = $fileWithData;
		//$this->storedPasswords = $fileWithData;
	}
	
	// Returnerar TRUE om det nya användarnamnet redan finns i listan av registrerade användare.
	public function compareNewUserNameWithList($newUsername)
	{
		if(in_array($newUsername, $this->storedUsernames))
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	// Sparar den nya användaren i textfilen med användare.
	public function saveNewUser($newUsername, $newPassword)
	{
		$stringToSave = $newUsername . ";" . $newPassword . PHP_EOL;

		// Finns inte filen, skapa den och spara den nya informationen.
		if(file_exists('userRegistry.txt') == FALSE)
		{
			$this->createNewTextFile($stringToSave, 'userRegistry');
		}
		else
		{
			// Hämta filens namn.
			$file = 'userRegistry.txt';
			
			// Hämta innehållet.
			$current = file_get_contents($file);
			
			// Lägg till den nya informationen längst bak i filen.
			$current .= $stringToSave;
			file_put_contents($file, $current);
		}
	}
	
	// Skapar en fil på servern som innehåller det medskickade objektets värden.
	public function createNewTextFile($value, $fileName)
	{
		// Skapar och öppnar en textfil.
		$file = fopen($fileName . ".txt", "w") or die("Unable to open file!");
		
		fwrite($file, $value);
		
		// Stänger textfilen.
		fclose($file);
	}
}
?>