
<?php
if(!isset($_SESSION)) 
{ 
	session_start();
}
if (!isset($_SESSION['logged']))
{
	header('Location: login.php');
	
}

$tid = $_POST["task_id"];
$cid = $_POST["course_id"];
$fileType = intval($_POST["accepted_file"]);

require_once 'include/db_handler.php';
$db = new DbHandler();
$userData = $db->data(); // dodatkowe zabezp

$target_dir = "uploads/".$userData["name"]."/".$tid;
if (!is_dir($target_dir)) {
mkdir($target_dir, 0700, true);
}
$fileName = basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . "/" . $fileName;
$uploadOk = 1;

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Allow certain file formats
if ($fileType == 0 && $imageFileType != "zip") {
    echo "Akceptowany format to ZIP";
    $uploadOk = 0;
	
}
elseif($fileType == 1 && $imageFileType != "pdf") {
    echo "Akceptowany format to PDF";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$db->addTaskAnswer($tid, $cid, $fileName);
		header('Location: task.php?id='.$tid.'');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
