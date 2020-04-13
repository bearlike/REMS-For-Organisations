<?php
	include("../header.php");
	$new_dbname="svcehost_forms";
	//Connecting to the database;
	$conn = new mysqli($servername, $username, $password, $new_dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error); // IF-Fail to Connect
	}

	$tablename = " `event_" . str_replace(" ","_",$_POST["event_name"]) . "` ";
	$attribute_list = "";
	$values_list = "";

	foreach ($_POST as $name => $value) { 
		//Include all attributes except event_name because it is for the table name
		if ($name != "event_name") {
			$attribute_list = $attribute_list . "`" . $name . "`" . ",";
			$values_list = $values_list . "\"" . $value . "\"" . ",";
		}
	}
	//Removing the last comma added
	$attribute_list = substr($attribute_list, 0, -1);
	$values_list = substr($values_list, 0, -1);
	$entry_query = "INSERT INTO" . $tablename . "(" . $attribute_list . ") VALUES (" . $values_list . ");";
	// echo $entry_query;

	$submit_stmt = $conn->prepare($entry_query);
	if (!$submit_stmt) {
		echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
	}
	$submit_stmt->execute();
	// echo ("<br> Value inserted successfully");
	header('Location: congrats.html');
?>
