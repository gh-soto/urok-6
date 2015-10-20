<?php
	session_start();
	session_destroy();
	unset($_SESSION);
	header("Location: http://" . $_SERVER['HTTP_HOST'] . "/task2.php");
?>