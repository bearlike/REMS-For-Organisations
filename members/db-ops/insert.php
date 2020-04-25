<?php
include("../header.php");
if (retIsAdmin($_SESSION['uname']) == 0) {
	header('Location: pages/error.php?error=noAccess');
}
try {
	$conn = new PDO('mysql:dbname=' . $_GET['db'] . ';host=' . $servername . ';charset=utf8', $username, $password);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	$message = $e->getMessage();
	header('Location: ../pages/error.php?error=Cannot connect to the server/database');
	die();
}
$attribute_list = "";
$values_list = "";
$tablename = $_POST["table"];
foreach ($_POST as $name => $value) { 
		//Include all attributes except table because it is for the table name
	if ($name != "table" && $name != "db") {
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
	header('Location: ../pages/error.php?error=Error Executing Query');
}
if ($_POST['db'] == $MainDB) $dbc = 1;
else if ($_POST['db'] == $formDB) $dbc = 2;
else $dbc = 3;
$submit_stmt->execute();
// echo("Value inserted successfully");			// For Debugging	
// echo ("<br> Value inserted successfully");	// For Debugging	
logActivity($_SESSION['uname'], 'Inserted column in [table=' . $tablename . '] of [db=' . $_POST['db'] . ']');
header('Location: ../db-manage.php?db=' . $dbc . '&table=' . $tablename);
?>