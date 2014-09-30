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
						<input type="text" name="requestedUsername" id="requestedUsername" value=""/>
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
		$usernameValidated = false;
		$passwordValidated = false;
		
		// Har "Registrera-knappen tryckts på valideras användarinput."
		if(isset($_POST['registerButton']) == TRUE)
		{
			// Kontrollerar användarnamnet.
			if(isset($_POST['requestedUsername']) == FALSE || $_POST['requestedUsername'] == '' || strlen($_POST['requestedUsername']) < 3)
			{
				// Visar felmeddelande.
				array_push($this->messages, "Användarnamnet har för få tecken. Minst 3 tecken");
			}
			else
			{
				// Användarnamnet är validerat.
				$usernameValidated = true;
			}
			
			// Kontrollerar lösenordet.
			if(isset($_POST['requestedPassword']) == FALSE || $_POST['requestedPassword'] == '' || strlen($_POST['requestedPassword']) < 6)
			{
				// Visar felmeddelande.
				array_push($this->messages, "Lösenordet har för få tecken. Minst 6 tecken");
			}
			else
			{
				// Kontrollerar det upprepade lösenordet.
				if(isset($_POST['repeatedRequestedPassword']) == FALSE || $_POST['repeatedRequestedPassword'] != $_POST['requestedPassword'])
				{
					// Visar felmeddelande.
					array_push($this->messages, "Lösenorden matchar inte");
				}
				else
				{
					// Lösenordet är validerat.
					$passwordValidated = true;
				}
			}
			
			// Om både användarnamnet och lösenordet är validerat...
			if($usernameValidated == TRUE && $passwordValidated == TRUE)
			{
				//...spara den nya användaren.
				echo "VALIDERAT OCH KLART! :D";
				
				// TODO FIXA SÅ ATT VALIDERAT ANVÄNDARNAMN VISAS I FÄLTET OM INTE LÖSENORDET GÅR IGENOM.
			}
		}
		
		$this->showRegistrationPage();
	}
}
?>