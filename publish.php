<?php
if(isset($_POST["comment"])) {
    if(!isset($_SESSION)) 
    { 
		session_start();
	}
if (!isset($_SESSION['logged']))
{
	return;
}
$comment = htmlspecialchars($_POST["comment"]);
$comment = str_replace(array("\n", "\r"), '', $comment);
require_once 'include/db_handler.php';
$db = new DbHandler();
$db->shout($comment);
}
?>
