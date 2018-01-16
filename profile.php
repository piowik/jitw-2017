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
	$pageId = 3;
	include 'header.php';
?>

<div class="content">
<div class="profile">
<div class="profile-photo">
  <?php
  function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

	$db = new DbHandler();
	$userData = $db->data(); // dodatkowe zabezp
	echo '<img src="images/profile/' . $userData['profilepic'] . '" width="120" height="120"><br>';?>
</div>
	<div class="profile-content">
	
	<?php echo '<h1>' . $userData['name'] . '</h1>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		Aktualizuj zdjecie:
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" value="Aktualizuj" name="submit">
	</form>			
	E-Mail: ' . $userData['email'] . '<br>
	Poprzednie logowanie: ' . date("m.d.Y H:i:s", strtotime($_SESSION["lastLogin"])) . '<br>
	Twoje IP: ' . get_client_ip() . '<br>
	Poziom: ';
	switch ($userData['access']) {
    case 0:
        echo "Użytkownik";
        break;
    case 1:
        echo "Nauczyciel";
        break;
    case 2:
        echo "Administrator";
        break;
}

?>
</div>
</div>
</div>
<?php
include 'footer.php';
?>

</div>
</body>
</html>  