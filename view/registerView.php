<?php
require_once('view/view.php');
require_once('controller/login.php');
require_once('model/registerModel.php');

class RegisterView extends View
{
	private $registerModel;
	private $messages;
	
	function __construct()
	{
		$this->registerModel = new RegisterModel();
		$this->messages = array();
	}
	// Visar registreringsformuläret.
	public function showRegistrationPage()
	{
		$this->header('Laborationskod ed222an');
		?>
		<a href="../labb4/">Tillbaka</a>
			<h2>Ej inloggad, Registrerar användare</h2>
			<form METHOD="post">
				<fieldset>
					<legend>Registrera ny användare - skriv in användarnamn och lösenord</legend>
					<?php
					// Loopar igenom messages-arrayen och skriver ut meddelanden.
						foreach ($this->messages as $value) {
							echo '<p>' . $value . '</p>';
						}
					?>
					<div>
						<label for="requestedUsername">Namn: </label>
						<input type="text" name="requestedUsername" id="requestedUsername" value="<?php echo $this->getUsername(); ?>"/>
					</div>
					<div>
						<label for="requestedPassword">Lösenord: </label>
						<input type="password" name="requestedPassword" id="requestedPassword"/>
					</div>
					<div>
						<label for="repeatedRequestedPassword">Repetera Lösenord: </label>
						<input type="password" id="repeatedRequestedPassword" name="repeatedRequestedPassword" value=""/>
					</div>
					<div>
						<label for="registerButton">Skicka: </label>
						<input type="submit" id="registerButton" name="registerButton" value="Registrera"/>
					</div>
				</fieldset>
			</form>
		<?php
		$this->footer();
	}

	// Validerar användarinput.
	public function validateUserInput()
	{
		$regexString = '/^[A-Za-z][A-Za-z0-9]{2,31}$/';
		$usernameValidated = FALSE;
		$passwordValidated = FALSE;
		
		// Har "Registrera-knappen tryckts på valideras användarinput."
		if(isset($_POST['registerButton']) == TRUE)
		{
			// Kontrollerar användarnamnet.
			if(isset($_POST['requestedUsername']) == FALSE || $_POST['requestedUsername'] == '' || strlen($_POST['requestedUsername']) < 3)
			{
				// Visar felmeddelande.
				array_push($this->messages, "Användarnamnet har för få tecken. Minst 3 tecken.");
			}
			else
			{
				// Kontrollerar om användarnamnet innehåller otillåtna tecken.
				if(!preg_match($regexString, $_POST['requestedUsername']))
				{
					// Tar bort de otillåtna tecknen.
					$_POST['requestedUsername'] = strip_tags($_POST['requestedUsername']);
					
					// Visar felmeddelande.
					array_push($this->messages, "Användarnamnet innehåller ogiltiga tecken.");
					
					// Visa registreringssidan.
					$this->showRegistrationPage();
					return FALSE;
				}
				else
				{
					// Kontrollera ifall användarnamnet är upptaget.
					if($this->registerModel->userExists($_POST['requestedUsername']))
					{
						// Visar felmeddelande.
						array_push($this->messages, "Användarnamnet är redan upptaget");
					}
					else
					{
						// Användarnamnet är validerat.
						$usernameValidated = TRUE;
					}
				}
			}
			
			// Kontrollerar lösenordet.
			if(isset($_POST['requestedPassword']) == FALSE || $_POST['requestedPassword'] == '' || strlen($_POST['requestedPassword']) < 6)
			{
				// Visar felmeddelande.
				array_push($this->messages, "Lösenordet har för få tecken. Minst 6 tecken.");
			}
			else
			{
				// Kontrollerar det upprepade lösenordet.
				if(isset($_POST['repeatedRequestedPassword']) == FALSE || $_POST['repeatedRequestedPassword'] != $_POST['requestedPassword'])
				{
					// Visar felmeddelande.
					array_push($this->messages, "Lösenorden matchar inte.");
				}
				else
				{
					// Lösenordet är validerat.
					$passwordValidated = TRUE;
				}
			}
			
			// Om både användarnamnet och lösenordet är validerat returnerar metoden TRUE.
			if($usernameValidated == TRUE && $passwordValidated == TRUE)
			{
				return TRUE;
			}
		}
		
		// Visa registreringssidan.
		$this->showRegistrationPage();
		return FALSE;
	}

	public function saveNewUser()
	{
		//...spara den nya användaren.
		$this->registerModel->saveUserToFile($_POST['requestedUsername'], $_POST['requestedPassword']);
	}
	
	// Hämtar användarnamnet ur $_POST-arrayen.
	public function getUsername()
	{		
		return $username = isset($_POST['requestedUsername']) ? $_POST['requestedUsername'] : "";
	}
}
?>