<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Rejestracja - elektroniczny dziennik</title>
<?php 
  
  if(isset($_COOKIE['style'])) {
	echo'<link rel="stylesheet" href="css/'.$_COOKIE['style'].'.css?v=1.0">';
  }
  else {
	  echo '<link rel="stylesheet" href="css/styles.css?v=1.0">';
  }
  ?>
</head>

<body>

<div class="main">
<?PHP

    if(!isset($_SESSION)) 
    { 
		session_start();
	}
	require_once 'include/db_handler.php';
	$registerCode = -1; // -1 - not registering, 0 - success, 1 - error
	$userResponse = "";
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($_POST['submit'])
		{	
	
			$login = $_POST["login"];
			$email = $_POST["email"];
			if (!isset($_POST["email"]) || empty($_POST["email"])) {
				$registerCode = 1;
				$userResponse = "Musisz podac email";
			}
			elseif (!isset($_POST["password"]) || empty($_POST["password"])){
				$registerCode = 1;
				$userResponse = "Musisz podac hasło";
			}
			elseif (!isset($_POST["login"]) || empty($_POST["login"])) {
				$registerCode = 1;
				$userResponse = "Musisz podac login";
			} 
			elseif (!isset($_POST["password_repeat"]) || empty($_POST["password_repeat"])) {
				$registerCode = 1;
				$userResponse = "Musisz powtorzyc hasło";
			} else {
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$userResponse = "Adres email: ".$email." jest niepoprawny";
					$registerCode = 1;
				}
			}
			if ($registerCode != 1) {
				$password = sha1($_POST["password"]);
				$passwordRep = sha1($_POST["password_repeat"]);
				if (strcmp($password, $passwordRep) !== 0) {
					$registerCode = 1;
					$userResponse = "Passwords doesnt match";
				}
				else {
					$db = new DbHandler();
					$response = $db->createUser($login, $password, $email, 0);
					if ($response["error_code"] == 0) {	
						$registerCode = 0;
						$userResponse = "Sukces. <a href=\"login.php\"> Kliknij aby przejsc do strony logowania</a>";
					}
					else {
						$registerCode = 1;
						$userResponse = $response["message"];
					}
				}
			}
		}
	}
?>


<?php
	$pageId = 1;
	include 'header.php';
?>

	<div class="content">
		<h1>Rejestracja</h1>

		<div class="content2">
			<?php 
				if ($registerCode == 0) 
					echo $userResponse;
				else {
					if ($registerCode == 1) 
						echo $userResponse;
			?>	
			<form action="register.php" method="post">
				Login: <input type="text" name="login" size="20"><br>
				Haslo: <input type="password" name="password" id="pwd" size="20">
				<p id="password-strength-text"></p>
				Powtorz haslo: <input type="password" name="password_repeat" size="20"><br>
				E-mail: <input type="text" name="email" size="30"><br>
				<input type="submit" class="registerbutton" name="submit" value="Submit"><br>
			</form>
			<?php
				}
			?>
		</div>
	</div>

	<script type="text/javascript" src="js/passwordstrength.js"></script>
		
<?php
include 'footer.php';
?>

</div>
</body>
</html>