<?php
     include("secrets_.php");
     $loggedOnUser="admin";
     $retProfilePic = new mysqli($servername, $username, $password, $dbname);
     if ($retProfilePic->connect_error) {
          die("Connection failed: " . $retProfilePic->connect_error);
     }
     $retProfilePicSQL = 'select imgsrc from login where LoginName="' . $loggedOnUser . '" limit 1;';
     echo $retProfilePicSQL; // For Debugging
     // echo "Successfully Logged"; // For Debugging
     $logResults  = $retProfilePic->query($retProfilePicSQL);
     foreach ($logResults as $row) {
          echo $startPath . '/assets/img/avatars/users/' . $row["imgsrc"];          
     }          
     $retProfilePic->close();
?>