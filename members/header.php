<?php
/*
* Header File
* For CMS For Organisations
*/
      // initialise
      session_start();  
      include("secrets_.php");      // Include Secret Keys such as APIs and override default database credentials
      
      // function to log
      // Call Example: logActivity($_SESSION['uname'], "Log Something"); 
      function logActivity($loggedOnUser, $log) {
            include("secrets_.php");
		$logActivityConn = new mysqli($servername, $username, $password, $dbname);
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

      
      // If Session doesn't exist
      if (!$_SESSION['uname']){
            header('Location: '.$startPath.'/members/member-login.php');    
      }
      else{
            if($_SESSION['remember']==0){
                  date_default_timezone_set('Asia/Kolkata');
                  $date1 = strtotime($_SESSION['loginTime']);
                  $date2 = strtotime(date('Y-m-d H:i:s'));
                  $secs = $date2 - $date1;// == <seconds between the two times>
                  $mins = $secs / 60;
                  // If inactive for 60 minuites
                  if($mins > 60){
                        session_destroy();
                        logActivity($_SESSION['uname'], 'Logged out due to inactivity');
                        header('Location: '.$startPath.'/members/member-login.php');
                  }
            }
            $loginUser = $_SESSION['uname'];
            $_SESSION['loginTime'] = date("Y-m-d H:i:s", time());
      }