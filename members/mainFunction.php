<?php
// function to log
// Call Example: logActivity($_SESSION['uname'], "Log Something");
function logActivity($loggedOnUser, $log) {
      include("secrets_.php");
  $logActivityConn = new mysqli($servername, $username, $password, $MainDB);
  if ($logActivityConn->connect_error) {
      die("Connection failed: " . $logActivityConn->connect_error);
  }
  $logSQL = 'CALL enterLog("' . $loggedOnUser . '","' . $log . '");';
  // echo $logSQL; // For Debugging
  if ($logActivityConn->query($logSQL) === TRUE) {
      // echo "Successfully Logged"; // For Debugging
  } else {
      // echo "Error: " . $logActivityConn->error; // For Debugging
      header('Location: '.$startPath.'/members/member-login.php');
  }
      $logActivityConn->close();
}

// returns 1 if user has admin privileges, else returns 0
// Call Example: retIsAdmin($_SESSION['uname']);
function retIsAdmin($loggedOnUser) {
      include("secrets_.php");
      $retIsAdmin = new mysqli($servername, $username, $password, $MainDB);
      if ($retIsAdmin->connect_error) {
            die("Connection failed: " . $retIsAdmin->connect_error);
      }
      $retIsAdminSQL = 'select count(1) as code from login where LoginName="' . $loggedOnUser . '" and IsAdmin=1;';
      // echo $retIsAdminSQL; // For Debugging
      $Results  = $retIsAdmin->query($retIsAdminSQL);
      foreach ($Results as $row) {
            return $row["code"];
      }
      $retIsAdmin->close();
}


// function to retieve profile pic
// Call Example: retProfilePic($_SESSION['uname']);
function retProfilePic($loggedOnUser) {
      include("secrets_.php");
      $retProfilePic = new mysqli($servername, $username, $password, $MainDB);
      if ($retProfilePic->connect_error) {
            die("Connection failed: " . $retProfilePic->connect_error);
      }
      $retProfilePicSQL = 'select imgsrc from login where LoginName="' . $loggedOnUser . '" limit 1;';
      // echo $retProfilePicSQL; // For Debugging
      $logResults  = $retProfilePic->query($retProfilePicSQL);
      foreach ($logResults as $row) {
            return $startPath . '/assets/img/avatars/users/' . $row["imgsrc"];
      }
      $retProfilePic->close();
}

?>
