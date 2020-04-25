<?php
// function to log
// Call Example: logActivity($_SESSION['uname'], "Log Something");
function logActivity($loggedOnUser, $log) {
      include("secrets_.php");
         $logActivityConn = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8',$username,$password);

         $logActivityConn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         $logActivityConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         $logSQL = $logActivityConn->prepare('CALL enterLog(:loggedOnUser,:log);');
         $logSQL->bindValue(":loggedOnUser",$loggedOnUser);
         $logSQL->bindValue(":log",$log);
         $logSQL->execute();



}

// returns 1 if user has admin privileges, else returns 0
// Call Example: retIsAdmin($_SESSION['uname']);
function retIsAdmin($loggedOnUser) {
      include("secrets_.php");
      $retIsAdmin = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);

      $retIsAdmin->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $retIsAdmin->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $retIsAdminSQL = $retIsAdmin->prepare('select count(1) as code from login where LoginName= :loggedOnUser and IsAdmin=1;');
      $retIsAdminSQL->bindValue(":loggedOnUser",$loggedOnUser);
      // echo $retIsAdminSQL; // For Debugging
      $retIsAdminSQL->execute();
      foreach ($retIsAdminSQL as $row) {
            return $row["code"];
      }
}


// function to retieve profile pic
// Call Example: retProfilePic($_SESSION['uname']);
function retProfilePic($loggedOnUser) {
      include("secrets_.php");
      $retProfilePic = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);
      $retProfilePic ->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $retProfilePic ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $retProfilePicSQL = $retProfilePic->prepare('select imgsrc from login where LoginName= :loggedOnUser limit 1;');
      $retProfilePicSQL->bindValue(":loggedOnUser",$loggedOnUser);

      $retProfilePicSQL->execute();

      foreach ($retProfilePicSQL as $row) {
            return $startPath . '/assets/img/avatars/users/' . $row["imgsrc"];
      }
}

?>
