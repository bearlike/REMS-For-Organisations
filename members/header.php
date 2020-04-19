<?php
/*
* Header File
* For CMS For Organisations
*/
      session_start();
      if (!$_SESSION['uname']){
            header('Location: /cms/members/member-login.php');
      }
      else{
            if($_SESSION['remember']==0){
                  date_default_timezone_set('Asia/Kolkata');
                  $date1 = strtotime($_SESSION['loginTime']);
                  $date2 = strtotime(date('Y-m-d H:i:s'));
                  $secs = $date2 - $date1;// == <seconds between the two times>
                  $mins = $secs / 60;
                  if($mins > 30){
                        session_destroy();
                        header('Location: /cms/members/member-login.php');
                  }
            }
            $loginUser = $_SESSION['uname'];
            /* database credentials START */
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'svcehost_cms';
            $formDB="svcehost_forms";
            /* Include Secret Keys such as APIs and override default database credentials */
            include("secrets.php");
            /* database credentials END */
            $_SESSION['loginTime'] = date("Y-m-d H:i:s", time());
      }

?>
