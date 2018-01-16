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
	$pageId = 7;
	include 'header.php';
?>

<div class="content">
		<?php
		
	$db = new DbHandler();
	$userData = $db->data();
	if ($userData['access'] == 0) {
		$courses = $db->getUserCourses($userData["user_id"]);
		if (count($courses) == 0)
			echo 'Nie jesteś zapisany do żadnego kursu';
		else {
			echo "<table><tr height=50 align=center><th>Twoje kursy</th><th>Wysłane rozwiązania</th></tr>";	
			foreach ($courses as $course) {
				$tasks = $db->getUserCourseTasksAnswers($userData['user_id'], $course['course_id']);
				echo '<tr align=center><td><a href="course.php?id='. $course['course_id'] . '">' . $course['name'] . '</a><br>Prowadzący: ' . $db->data($course['owner'])['name']. "</td>";
				echo '<td>';
				$points = 0;
				$maxPoints = 0;
				foreach($tasks as $task) {
					echo '<b>' . $task["name"] . '</b> ';
					if ($task["note"] != -1) { 
						$points += $task["note"];
						$maxPoints += $task["maxNote"];
						echo $task["note"] . '/' . $task["maxNote"] . ' ('.$task["timeChecked"].')';
					}
					else
						echo "Nie oceniono";
					echo '<br>';
				}
				$per = $points/$maxPoints * 100;
				echo '<b>Stan punktów: '.$points.'/'.$maxPoints.'</b> ('.round($per,2).'%)<br>';
				echo '</td>';
				echo '</tr>';
			}
			echo "</table>";
		}
	}
	else if ($userData['access'] == 1) {
		$courses = $db->getTeacherCourses($userData["user_id"]);
		echo "<table><tr height=50 align=center><th>Twoje kursy</th></tr>";	
		foreach ($courses as $course) {
			echo '<tr align=center><td><a href="course.php?id='. $course['course_id'] . '">' . $course['name'] . '</a></td>';
			echo '</tr>';
		}
	echo "</table>";
		
	}
?>
</div>
<?php
include 'footer.php';
?>
</div>

		
</body>
</html>  