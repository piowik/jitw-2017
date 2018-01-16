<?php

    if(!isset($_SESSION)) 
    { 
		session_start();
	}
	if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
	}	
	require_once 'include/db_handler.php';

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$db = new DbHandler();
		if (isset($_POST['submit']))
		{	
			$login = $_POST["login"];
			$email = $_POST["email"];
			$password = sha1($_POST["password"]);
			$access = $_POST["access"];
			$response = $db->createUser($login, $password, $email, $access);

			echo $response["message"];
		}
		else if (isset($_POST['change'])) {
			$db->setUserAccess($_POST['id'], $_POST['targetaccess']);
			echo 'OK<br>';
		}
	}
?>
<html>
<head>

  <title>Elektroniczny dziennik</title>
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

<?php
	$pageId = 4;
	include 'header.php';
?>

<div class="content">
<h1>Panel administratora</h1>
<?php
	$db = new DbHandler();
	$userData = $db->data(); // dodatkowe zabezpieczenie do sprawdzenia praw dostepu
	if ($_SESSION['access'] == 2 && $userData['access'] == 2) {
		echo '<div class="expandable-content">
				<h2>Dodaj użytkownika</h2>
	<form action="adminpanel.php" method="post">
	Login: <input type="text" name="login" size="20"><br>
	Haslo: <input type="text" name="password" size="20"><br>
	E-mail: <input type="text" name="email" size="30"><br>
	Pozycja: 
	<select name="access">
        <option selected="selected" value="0">Użytkownik</option>
        <option value="1">Nauczyciel</option>
        <option value="2">Administrator</option>
      </select>

	<input type="submit" name="submit" value="Submit">
	</form>
	</div>';
		$users = $db->getAllUsers();;
	echo "<table><tr align=center><th>ID</th>
	<th>Pic</th>
	<th>Login</th>
	<th>Access</th>
	<th>Last login</th>
	<th>Akcje</th></tr>";
			
	foreach ($users as $user) {
		echo "<tr align=center><td>". $user['user_id'] . '</td><td><img src="images/profile/' . $user['profilepic'] . '" width="80" height="80"></td><td>' . $user['name'] . '</td><td>'. $user['access'] .'</td><td>' . date("d.m.Y H:i:s", strtotime($user['lastLogin'])) . '</td>';
		
		if ($user['access'] == 0) {
			echo '<td><form action="adminpanel.php" method="post">
			<input type="hidden" name="id" id="hiddenField" value='.$user['user_id'].' />
			<input type="hidden" name="targetaccess" id="hiddenField" value="1" />
			<input type="submit" name="change" value="Ustaw status: nauczyciel">
			</form></td>';
		}
		else if ($user['access'] == 1) {
			echo '<td><form action="adminpanel.php" method="post">
			<input type="hidden" name="id" id="hiddenField" value='.$user['user_id'].' />
			<input type="hidden" name="targetaccess" id="hiddenField" value="0" />
			<input type="submit" name="change" value="Ustaw status: uczeń">
			</form></td>';
		}
		else 
			echo '<td>Brak możliwych akcji</td>';
		echo '</tr>';
	}
	echo "</table>";
?>

	
	<?php
	}
	else {
		echo "Nie masz wystarczajacych praw aby wyswietlic strone.";
	}
?>
</div>
<?php
include 'footer.php';
?>

</div>

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="./js/showhide.js"></script>
</body>
</html>  