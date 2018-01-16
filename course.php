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
	$db = new DbHandler();
	
		$id = $_GET["id"];
	$course = $db->courseData($id);
	$userData = $db->data();
	$registerCode = -1; // -1 - not registering, 0 - success, 1 - error
	$userResponse = "";
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['submit']))
		{	
				$password = sha1($_POST["password"]);
				$response = $db->signToCourse($id, $password, $userData["user_id"]);
				if ($response["error_code"] == 0) {	
					$registerCode = 0;
					$userResponse = "Sukces.";
				}
				else {
					$userResponse = $response["message"];
				}
		}
		else if (isset($_POST['task'])) {
			
			$taskname = $_POST["taskname"];
			$desc = $_POST["desc"];
			$acceptedFiles = $_POST["acceptedFiles"];
			$maxnote = $_POST["maxnote"];
			$deadline = $_POST["deadline"];
			
			$timestamp = date('Y-m-d H:i:s', strtotime($deadline));
			$db->createTask($id, $taskname, $desc, $maxnote, $acceptedFiles, $timestamp);			  
		}
	}
?>
<html>
<head>

  <title>Kurs - Elektroniczny dziennik</title>
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

		<?php
		$showTasks = false;
		if(isset($id) and $id != null) {
			
			echo '<h1>' .$course["name"] . '</h1>';
			if ($course["owner"] == $userData["user_id"]) {
				$showTasks = true;
			echo '<div class="expandable-content">
			<h2>Dodaj zadanie</h2>
			<form action="course.php?id='. $id .'" method="post">
				Nazwa zadania: <input type="text" name="taskname" size="20"><br>
				Opis: <textarea name="desc" rows="3" cols="40"></textarea><br>
				Maksymalna ocena: <input type="text" name="maxnote" size="20"><br>
				Akceptowane pliki:  <select name="acceptedFiles">
				  <option value="0">zip</option>
				  <option value="1">pdf</option>
				  <option value="2">dowolny</option>
				</select> <br>
				Termin oddania: <input type="text" id="datepicker" name="deadline" size="20"><br>
								
				<input type="submit" name="task" value="Dodaj zadanie"><br>
			</form>
			</div>';	
			}
			elseif ($userData["access"] == 1)
				echo "To nie twoj kurs";
			elseif ($userData["access"] == 2) {
				echo "Widok administratora";
				$showTasks = true;
			}
			else {
				if ($db->isUserInCourse($userData["user_id"],$id)) {
					$showTasks = true;
				}
				else {
					echo "Mozesz sie zapisać<br>";
					echo $userResponse;

			echo '<form action="course.php?id='.$id.'" method="post">
				Haslo: <input type="password" name="password" id="pwd" size="20">
				<input type="submit" class="registerbutton" name="submit" value="Zapisz się"><br>
			</form>';	

				}
			}
			if ($showTasks) {
				$tasks = $db->getAllCourseTasks($id);
	echo "<table><tr height=50 align=center><th>Nazwa</th><th>Opis</th><th>Czas oddawania</th></tr>";
	
	foreach ($tasks as $task) {
		echo '<tr align=center><td><a href="task.php?id='. $task['task_id'] . '">' . $task['name'] . '</a><br>';
		
		if ($userData["access"] == 0){
		if($db->hasGivenTask($task['task_id'], $userData['user_id']))
			echo 'Oddano';
		else
			echo 'Nie oddano';
		}
		
		$today = date("Y-m-d H:i:s");
		if ($task['deadline'] < $today)
			echo ' (Termin minął)';
		echo '</td><td>'.$task['description'].'</td>
		<td>' . date("d.m.Y H:i:s", strtotime($task['deadline'])) . '</td></tr>';
	}
	echo "</table>";
			}
		}
?>
</div>
<?php
include 'footer.php';
?>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#datepicker" ).datepicker({
      inline: true,
      showOtherMonths: true,
      dayNamesMin: ['Nie', 'Pon', 'Wt', 'Śr', 'Czw', 'Pią', 'Sob'],
    });
  } );
  </script>
        <script src="./js/showhide.js"></script>
</body>
</html>  