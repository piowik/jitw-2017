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
	$task = $db->taskData($id);
	$course = $db->courseData($task["course_id"]);
	$userData = $db->data();
	
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if (isset($_POST['submit']))
		{
			$aid = $_POST["answer_id"];
			
			$note = $_POST["note"];
			if (!isset($note) || $note < 0 || $note > $task["maxNote"]) {
				echo "Nie tak.";
				return;
			}
			echo "Wołam" . $aid . ":" . $note;
			$db->setTaskAnswerNote($aid, $note);
		}
	}
?>
<html>
<head>

  <title>Dodaj rozwiazanie - Elektroniczny dziennik</title>
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
			if ($userData["access"] == 0) {
			if ($db->isUserInCourse($userData["user_id"],$task["course_id"])){
				echo '<h1>' .$course["name"] . '</h1>';
				echo '<h2>' .$task["name"] . '</h2>';
				echo $task['description'].'<br>';
				$deadline = $task["deadline"];
				$today = date("Y-m-d H:i:s");
				echo 'Czas oddawania: ' . $deadline .'<br>';
				if($db->hasGivenTask($task['task_id'], $userData['user_id'])) {
					$answer = $db->taskAnswerData($task['task_id'], $userData['user_id']);
					echo 'Twoje rozwiązanie: <a href="uploads/'.$userData["name"].'/'.$id.'/'.$answer["filename"].'">'.$answer["filename"] .'</a><br>';
					echo 'Przeslano do oceny: '. $answer["timeAdded"] .'<br>';
					$ocena = $answer["note"];
					if ($ocena >= 0) {
						echo "Oceniono: " . $answer["timeChecked"] . '<br>';
						echo "Ocena: " . $ocena . "/" . $task["maxNote"] ."<br>";
					}
					else
						echo "Czeka na ocenę";
					
				}
				else {
					if ($deadline > $today) {
						echo '<form action="taskupload.php" method="post" enctype="multipart/form-data">
								Wyślij rozwiązanie (Akceptowany format: ';
								switch ($task["acceptedFile"]) {
									case 0:
										echo 'zip';
										break;
									case 1:
										echo 'pdf';
										break;
									case 2:
										echo 'dowolny';
										break;
								}
								
								echo')<br>
								<input type="file" name="fileToUpload" id="fileToUpload">
								
								<input type="hidden" name="task_id" id="hiddenField" value='.$id.' />
								<input type="hidden" name="course_id" id="hiddenField" value='.$task["course_id"].' />
								<input type="hidden" name="accepted_file" id="hiddenField" value='.$task["acceptedFile"].' />
								<input type="submit" value="Wyślij" name="submit">
							</form>';
					}
					else
						echo 'Termin minął.';
				}
			}
			else
				echo "Musisz byc zapisany do kursu aby dodawac rozwiazania.";
			}
			else {
				
				$answers = $db->getTaskAnswersById($id);
				echo 'Nadesłanych rozwiązań: ' . count($answers) . '<br>
				Maksymalna ocena: '.$task["maxNote"].'';
				echo "<table>
				<th>Użytkownik</th>
				<th>Plik</th>
				<th>Ocena</th>
				<th>Data nadesłania</th>";
						
				foreach ($answers as $answer) {
					$uData = $db->data($answer["user_id"]);
					echo '<tr>
					<td>'.$uData["name"].'</td>
					<td><a href="uploads/'.$uData["name"].'/'.$id.'/'.$answer["filename"].'">'.$answer["filename"] .'</a></td>';
					if ($answer["note"] == -1) {
						echo '<td><form action="task.php?id='.$id.'" method="post">
							<input type="hidden" name="answer_id" id="hiddenField" value='.$answer["answer_id"].' />
							<input type="number" name="note" min="0" value="0" max='.$task["maxNote"].'>
							<input type="submit" name="submit" value="Oceń">
							</form></td>';
					}
					else {
						echo '<td>'.$answer["note"].'/'.$task["maxNote"].'</td>';
					}
					echo'<td>'.$answer["timeAdded"].'</td>
					</tr>';
				}
				echo '</table>';
			}
		}
		?>
			
</div>
<?php
include 'footer.php';
?>

</div>
</body>
</html>  