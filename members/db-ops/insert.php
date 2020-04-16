<?php
include("../header.php");
	$conn = new mysqli($servername, $username, $password, $formDB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	$attribute_list = "";
	$values_list = "";
	$tablename = $_POST["event_name"];
	foreach ($_POST as $name => $value) { 
		//Include all attributes except event_name because it is for the table name
		if ($name != "event_name" && $name!="dbname") {
			$attribute_list = $attribute_list . "`" . $name . "`" . ",";
			$values_list = $values_list . "\"" . $value . "\"" . ",";
		}
	}
	//Removing the last comma added
	$attribute_list = substr($attribute_list, 0, -1);
	$values_list = substr($values_list, 0, -1);
	$entry_query = "INSERT INTO " . $tablename . "(" . $attribute_list . ") VALUES (" . $values_list . ");";
	// echo $entry_query;

	$submit_stmt = $conn->prepare($entry_query);
	if (!$submit_stmt) {
		echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
	}
	$submit_stmt->execute();
	echo("Value inserted successfully");
	// echo ("<br> Value inserted successfully");
	header('Location: ../db-manage.php?event='.$_POST["event_name"].'&dbname='.$_POST["dbname"]);
?>