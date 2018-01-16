<?php

    if(!isset($_SESSION)) 
    { 
		session_start();
	}
session_unset();
header('Location: index.php');
?>