<?php

session_start();

if(isset($_SESSION['connect'])){
	header('location: ../');
}

require('src/connexion.php');

if(!empty($_POST['email']) && !empty($_POST['password'])){

	$email 		= $_POST['email'];
	$password 	= $_POST['password'];

	$password = "aq1".sha1($password."82729")."172";

	$req;		= $db->prepare('SELECT * FROM users WHERE email = ?');
	$req->execute(array($email));

	while($user = $req->fetch()){

		if($password == $user['password']){
			$error = 0;
			$_SESSION['connect'] 	= 1;
			$_SESSION['pseudo']		= $user['pseudo'];

			if(isset($_POST['connect'])){

				setcookie('log', $user['secret'], time() +365*24*3600, '/', null, false, true);
			}

			header('location: ../connexion.php?success=1')

		// }else {
		// 	header('location: ../connexion.php?error=1');
		// }
	}

	header('location: ../connexion.php?error=1');

}



?>


<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<title>Espace Membre - Connexion</title>
		<link rel="stylesheet" type="text/css" href="design/default.css" / >
	</head>

	<body>

	<header>
		<h1>Connexion</h1>
	</header>
	
		<div class="container">
		
			<p id="info">Bienvenue sur mon site, si vous n'etes pas inscrit, <a href="index.php">inscrivez-vous</a>.</p>

			<?php

			if(isset($_GET['error'])){
				echo '<p id="error">Nous ne pouvons pas vous identifier</p>';
			}
			else if(isset($_GET['success'])){
				echo'<p id="success>Vous etes maintenant connecté</p>'
			}

			?>

			<div id="form">
				<form method="post" action="index.php">

					<table>
			
						<tr>
							<td>Email</td>
							<td><input type="email" name="email" placeholder="Ex : Jean@monmail.com" required></td>
						</tr>

						<tr>
							<td>Mot de passe</td>
							<td><input type="password" name="password" required></td>
						</tr>
				
					</table>
					<p><label><input type="checkbox" name="connect" checked>connexion automatique</label></p>
					<div id="button">
						<button>Connexion</button>
					</div>
						
				</form>
			</div>	

			<?php
			} else { ?>
				<p id="info">
					Bonjour <?= $_SESSION['pseudo']  ?><br>
					<a href="src/deconnexion.php">Deconnexion</a>
				</p>

			<?php }
			?>	
		</div>
	</body>

</html>