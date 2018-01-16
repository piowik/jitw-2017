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
  <title>Kursy - Elektroniczny dziennik</title>
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
	$pageId = 6;
	include 'header.php';
?>

<div class="content">
<h1>Kursy</h1>
		<?php
		
	$db = new DbHandler();
		$courses = $db->getAllCourses();
	echo "<table><tr align=center><th>Nazwa</th><th>Opis</th><th>Utworzony</th></tr>";
	
	foreach ($courses as $course) {
		echo '<tr align=center><td><a href="course.php?id='. $course['course_id'] . '">' . $course['name'] . '</a><br>Prowadzi:' .  $db->data($course['owner'])['name']. ' </td>
		<td>'. $course['descr'].'</td>
		<td>' . date("d.m.Y H:i:s", strtotime($course['created'])) . '</td></tr>';
	}
	echo "</table>";
?>

</div>

<?php
include 'footer.php';
?>

</div>

		
</body>
</html>  