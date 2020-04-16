<?php
	include("../header.php");
	$conn = new mysqli($servername, $username, $password, $formDB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $table_name = $_GET["event"];
    $id = $_GET["id"];

    $delete_query = "DELETE FROM ".$table_name." WHERE id='".$id."';";
    $submit_stmt = $conn->prepare($delete_query);
    if (!$submit_stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
    }
    $submit_stmt->execute();
	header('Location: ../db-manage.php?event='.$_GET["event"].'&dbname='.$_GET['dbname']);
?>