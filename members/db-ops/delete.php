<?php
	include("../header.php");
	$conn = new mysqli($servername, $username, $password, $_GET['db']);
    // Check connection
    if ($conn->connect_error) {
       header('Location: ../pages/error.php?error=Cannot connect to the server/database');
    }

    $tablename = $_GET["table"];
    $id = $_GET["id"];

    $delete_query = "DELETE FROM ".$tablename." WHERE id='".$id."';";
    $submit_stmt = $conn->prepare($delete_query);
    if (!$submit_stmt) {
        header('Location: ../pages/error.php?error=Error while executing the query');
    }
    $submit_stmt->execute();
     if($_GET['db']==$dbname)
         $dbc=1;
     else
         $dbc=2;
    logActivity($_SESSION['uname'], 'Removed column for [id=' . $id . '] in [table=' . $tablename . '] of [db=' . $_GET['db'] . ']');
	header('Location: ../db-manage.php?db='.$dbc.'&table='.$tablename);
?>