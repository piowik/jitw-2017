<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Dodaj kurs - elektroniczny dziennik</title>
<?php 
  
  if(isset($_COOKIE['style'])) {
	echo'<link rel="stylesheet" href="css/'.$_COOKIE['style'].'.css?v=1.0">';
  }
  else {
	  echo '<link rel="stylesheet" href="css/styles.css?v=1.0">';
  }
  ?>
</head>

<div class="main">
<body>
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
	
			$name = $_POST["coursename"];
			$desc = $_POST["desc"];
			$password = sha1($_POST["password"]);
			$passwordRep = sha1($_POST["password_repeat"]);
			if (strcmp($password, $passwordRep) !== 0) {
				$registerCode = 1;
				$userResponse = "Hasla sie nie zgadzaja";
			}
			else {
				$db = new DbHandler();
				$response = $db->createCourse($name, $desc, $password, $_SESSION['user_id']);
				if ($response["error_code"] == 0) {	
					$registerCode = 0;
					$userResponse = "Sukces.";
				}
				else {
					$registerCode = 1;
					$userResponse = $response["message"];
				}
			}
			
		}
	}
?>


<?php
	$pageId = 5;
	include 'header.php';
?>

	<div class="content">
		<h1>Dodaj kurs</h1>

		<div class="content2">
			<?php 
				if ($registerCode == 0) 
					echo $userResponse;
				else {
					if ($registerCode == 1) 
						echo $userResponse;
			?>	
			<form action="addcourse.php" method="post">
				Nazwa kursu: <input type="text" name="coursename" size="20"><br>
				Haslo: <input type="password" name="password" id="pwd" size="20">
				Powtorz haslo: <input type="password" name="password_repeat" size="20"><br>
				Opis: <textarea name="desc" rows="3" cols="40"></textarea><br>

				<input type="submit" class="registerbutton" name="submit" value="Submit"><br>
			</form>
			<?php
				}
			?>
		</div>
	</div>
		
<?php
include 'footer.php';
?>
</div>
</body>
</html>