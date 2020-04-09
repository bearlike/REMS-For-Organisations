<?php 
    session_start();
    if (!$_SESSION['uname']){
          header('Location: member-login.php');    
    }
    else{
          $loginUser = $_SESSION['uname'];
          /* database credentials START */
          $servername = 'localhost';
          $username = 'root';
          $password = '';
          $dbname = 'svcehost_cms';
          /* database credentials END */
    }
     
?>
