<?php
include("../header.php");
	$conn = new mysqli($servername, $username, $password, $_POST['db']);
    // Check connection
    if ($conn->connect_error) {
        header('Location: ../pages/error.php?error='.$conn->connect_error);
    }
	$attribute_list = "";
	$values_list = "";
	$tablename = $_POST["table"];
	foreach ($_POST as $name => $value) { 
		//Include all attributes except table because it is for the table name
		if ($name != "table" && $name!="db") {
			$attribute_list = $attribute_list . "`" . $name . "`" . ",";
			$values_list = $values_list . "\"" . $value . "\"" . ",";
		}
	}
	//Removing the last comma added
	$attribute_list = substr($attribute_list, 0, -1);
	$values_list = substr($values_list, 0, -1);
	$entry_query = "INSERT INTO " . $tablename . "(" . $attribute_list . ") VALUES (" . $values_list . ");";
	//echo $entry_query;

	$submit_stmt = $conn->prepare($entry_query);
	if (!$submit_stmt) {
		header('Location: ../pages/error.php?error='.$conn->error);
	}
     if($_POST['db']==$dbname)
         $dbc=1;
     else
         $dbc=2;
	$submit_stmt->execute();
	// echo("Value inserted successfully");			// For Debugging	
	// echo ("<br> Value inserted successfully");	// For Debugging	
	logActivity($_SESSION['uname'], 'Inserted column in [table=' . $tablename . '] of [db=' . $_POST['db'] . ']');
	header('Location: ../db-manage.php?db='.$dbc.'&table='.$tablename);
?>