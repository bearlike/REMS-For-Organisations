<?php
if (!empty( $_POST)) {
    session_start(); 
    // Runtime Variables
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "svcehost_cms";
    $uname = NULL;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Call Procedures
    $check_sql = "CALL CheckUser(\"".$_POST['uname']."\",\"".$_POST['password']."\");";
    $result = $conn->query($check_sql);
    foreach ($result as $row) {
        if ($row["code"]>0) {
            $flag=true;
        }
        else {
            $flag=false;
        }
    }
    
    if ($flag==false) {       
        $conn->close();     
        echo "Unsuccessful";  
        header('Location: member-login.php');
    }
    else {
        $_SESSION['uname'] = $_POST['uname'];
        $conn->close();
        header('Location: dashboard.php');
    }
}
else {
    header('Location: member-login.php');
}
?>