<?php
/*
* Basic Logout
* For CMS For Organisations
*/
     session_start();
     session_destroy();
     header('Location: member-login.php');
?>