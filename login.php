<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Logowanie - elektroniczny dziennik</title>

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
	require_once 'include/db_handler.php';

    if(!isset($_SESSION)) 
    { 
		session_start();
	}
	$loginCode = -1;
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ($_POST['submit'])
		{	
			$login = $_POST["login"];
			$password = sha1($_POST["password"]);
			$db = new DbHandler();
			$user = $db->loginUser($login, $password);
			if ($user["error"]) {
				$loginCode = 1;
			}
			else {
				$_SESSION['logged'] = true;
				$_SESSION['user_id'] = $user["user_id"];
				$_SESSION['name'] = $user["name"];
				$_SESSION['email'] = $user["email"];
				$_SESSION['access'] = $user["access"];			
				$_SESSION['profilepic'] = $user["profilepic"];			
				$_SESSION['lastLogin'] = $user["lastLogin"];		
				header('Location: profile.php');
			}
		}
	}
?>
<?php
	$pageId = 2;
	include 'header.php';
?>
	<div class="content">
	<h1>Logowanie</h1>
		<div class="content2">
			<?php
			if ($loginCode == 1) {
				echo "Nieprawidłowy login lub hasło";
			}
			?>
			<form action="login.php" method="post">
				Login: <input type="text" name="login" size="20"><br>
				Haslo: <input type="password" name="password" size="20"><br>
				<input type="submit" class="registerbutton" name="submit" value="Submit"><br>
			</form>
		</div>
	</div>
	<?php
	include 'footer.php';
	?>
	
</div>
</body>
</html>