<?php
require '../config/config.php';
session_destroy();
header('location: ../index.php');
exit();

?>
