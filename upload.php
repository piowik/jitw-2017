
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
$target_dir = "images/profile/";
$initName = basename($_FILES["fileToUpload"]["name"]);
$path = pathinfo($initName);
$fileName = time() . '.' . $path["extension"];

$target_file = $target_dir . $fileName;
echo $target_file;
$uploadOk = 1;

$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG and PNG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$db = new DbHandler();
		$db->updatePhoto($_SESSION['user_id'], $fileName);
		header('Location: profile.php');
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
