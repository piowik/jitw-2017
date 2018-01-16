<?php
require_once 'include/db_handler.php';
$db = new DbHandler();
header('Content-type: application/json');
echo json_encode($db->getShouts());
?>