<?php

class RegisterModel
{
	private $textFileName = 'userRegistry.txt';
	
	public function __construct()
	{
		
	}
	
	// Returnerar TRUE om det nya användarnamnet redan finns i listan av registrerade användare.
	public function userExists($newUsername)
	{
		// Kontrollerar ifall filen finns.
		if($this->checkForFile($this->textFileName))
		{
			// Bryter ut alla användare vid ny rad.
			$file = file_get_contents($this->textFileName);
			$result = explode(PHP_EOL, $file);
			
			foreach($result as $user)
			{
				// Bryter ut användarnamn och lösenord vid semikolon.
				$userAndPassword = explode(";", $user);
				
				// Kontrollerar ifall användarnamnet är detsamma som det nya användarnamnet.
				if($userAndPassword[0] == $newUsername)
				{
					return TRUE;
				}
			}
		}
		
		return FALSE;
	}
	
	// Söker efter given fil.
	public function checkForFile($fileName)
	{
		if(file_exists($fileName) == TRUE)
		{
			return TRUE;
		}
		
		return FALSE;	
	}
	
	// Sparar den nya användaren i textfilen med användare.
	public function saveUserToFile($newUsername, $newPassword)
	{
		$stringToSave = $newUsername . ";" . $newPassword . PHP_EOL;

		// Finns inte filen, skapa den och spara den nya informationen.
		if($this->checkForFile($this->textFileName) == FALSE)
		{
			$this->createNewFile($stringToSave, $this->textFileName);
		}
		else
		{	
			// Hämta innehållet.
			$current = file_get_contents($this->textFileName);
			
			// Lägg till den nya informationen längst bak i filen.
			$current .= $stringToSave;
			file_put_contents($this->textFileName, $current);
		}
	}
	
	// Skapar en fil på servern som innehåller det medskickade objektets värden.
	public function createNewFile($value, $fileName)
	{
		// Skapar och öppnar en fil.
		$file = fopen($fileName, "w") or die("Unable to open file!");
		
		fwrite($file, $value);
		
		// Stänger filen.
		fclose($file);
	}
}
?>