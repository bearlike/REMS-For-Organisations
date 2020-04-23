<?php
     include("../header.php");
     $alertConn = new mysqli($servername, $username, $password, $dbname);
     if ($alertConn->connect_error) {
     	die("Connection failed: " . $alertConn->connect_error);
     }
     $alertSQL = 'INSERT INTO `notification` (`user`, `message`, `type`, `clickURL`) VALUES ("'.$_SESSION['uname'].'", "'.$_POST["alertMessage"].'", "'.$_POST["type"].'", "#");';
     // echo $logSQL; // For Debugging
     if ($alertConn->query($alertSQL) == TRUE) {
     	header('Location: ../dashboard.php?alPush=Success');
     } else {
     	// echo "Error: " . $alertConn->error; // For Debugging
     	header('Location: ../member-login.php');
     }
     $alertConn->close();
?>