<?php
require_once '../config.inc.php';
	unset($_SESSION['userId']);
	setcookie('userId','',time()-3600,'/');
header("location:../index.php");
?>