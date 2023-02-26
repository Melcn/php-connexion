<?php

session_start();


require('src/connexion.php');

if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])){

	$pseudo               	= $_POST['pseudo'];
	$email      			= $_POST['email'];
	$password     			= $_POST['password'];
	$password_confirm     	= $_POST['password_confirm'];


	//TEST SI PASSWORD = PASSWORD CONFIRM
	if ($password != $password_confirm) {
		header('location: ../?error=1&pass=1');
	}

	//TEST SI EMAIL DEJA UTILISE
	$req = $db->prepare("SELECT count(*) as numberEmail FROM users WHERE email = ?");
	$req->execute(array($email));

	while($email_verification = $req->fetch()){
		if($email_verification['numberEmail'] != 0){
			header('location: ../?error=1&email=1');
		}
	}

	//HASH

	$secret = sha1($email).time();
	$sercet = sha1($secret).time().time();

	//CRYPTAGE DU PASSWORD (GRAIN DE SEL)
	$password = "aq1".sha1($password."82729")."172";

	//ENVOI DE LA REQUETE
	$req = $db->prepare("INSERT INTO users(pseudo, email, password, secret) VALUES(?,?,?,?)");
	$req ->execute(array($pseudo, $email, $password, $sercret));

	header('location: ../?success=1');

}
?>


<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>Espace Membre</title>
		<link rel="stylesheet" type="text/css" href="design/default.css" / >
	</head>

	<body>


		<header>
			<h1>Inscription</h1>
		</header>

				
		<div class="container">	

			<?php
				if(!isset($_SESSION['connect'])){


			?>

				<p id="info">Bienvenue sur mon site, pour en voir plus, inscrivez-vous. Sinon, <a href="connexion.php">connectez-vous</a>.</p>

				<?php
					if(isset($_GET['error'])){

						if (isset($_GET['pass'])) {
							echo '<p id="error">Les mots de passe ne correspodent pas.</p>';
						}	
						else if (isset($_GET['email'])) {
							echo '<p id="error">Cette email est deja utilisé.</p>';
						}

					}else if(isset($_GET['success'])) {
							echo '<p id"success">Votre inscription à  été realisé avec succes!</p>';
					}
				?>

				<div id="form">
					<form method="post" action="index.php">

						<table>
							<tr>
								<td>Pseudo</td>
								<td><input type="text" name="pseudo" placeholder="Ex : Jean" required></td>
							</tr>
						
							<tr>
								<td>Email</td>
								<td><input type="email" name="email" placeholder="Ex : Jean@monmail.com" required></td>
							</tr>

							<tr>
								<td>Mot de passe</td>
								<td><input type="password" name="password" required></td>
							</tr>
						
							<tr>
								<td>Confirmer mot de passe</td>
								<td><input type="password" name="password_confirm" required></td>
							</tr>
						</table>


						<div id="button">
							<button>Inscription</button>
						</div>
						
					</form>
				</div>

		</div>

	</body>

</html>