<?php
/*
* Basic Logout
* For CMS For Organisations
*/
     session_start();
     session_destroy();
	setcookie("username","", time()-3600);	// forget cookie
     setcookie("password","", time()-3600);	// forget cookie
     header('Location: member-login.php');
?>