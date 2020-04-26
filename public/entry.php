<?php
	include("pheader.php");
	//Connecting to the database;
	try{
		$conn = new PDO('mysql:dbname='.$formDB.';host='.$servername.';charset=utf8', $username, $password);

		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $e){
	$message = $e->getMessage()  ;
	header('Location:error.html');
	die();
}
	$table_list=[];


	$select_tables=$conn->prepare("SHOW TABLES;");
	$select_tables->execute();
	foreach($select_tables as $table){
		$table_list[]= $table["Tables_in_".$formDB];
	}

	$tablename = "event_" . str_replace(" ","_",$_POST["event_name"]) . "";
	if(in_array($tablename,$table_list)){
		$values_list = "";
		$i=0;

		foreach ($_POST as $name => $value) {
			//Include all attributes except event_name because it is for the table name
			if ($name != "event_name") {
				$values_list = $values_list . ":" . "value".$i .",";
				$i++;
			}
		}
		//Removing the last comma added
		$values_list = substr($values_list, 0, -1);
		$entry_query = $conn->prepare("INSERT INTO ".$tablename . " VALUES ( NULL, NOW()," . $values_list . ");");


		$i=0;
		foreach ($_POST as $name => $value) {
			//Include all attributes except event_name because it is for the table name
			if ($name != "event_name") {
				$entry_query->bindValue(":value".$i, $value);
				$i++;
			}
		}
		$entry_query->execute();

		 header('Location: congrats.html');
	}else{
		header('Location: bad-request.html');
	}

?>
