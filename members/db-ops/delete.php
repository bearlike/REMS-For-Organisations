<?php
    include("../header.php");
    if(retIsAdmin($_SESSION['uname'])==0){
        header('Location: pages/error.php?error=noAccess');
    }
    try{
        $conn =  new PDO('mysql:dbname='.$_GET['db'].';host='.$servername.';charset=utf8', $username, $password);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
        $message = $e->getMessage()  ;
        header('Location: ../pages/error.php?error=Cannot connect to the server/database');
        die();
    }

    $tablename = $_GET["table"];
    $id = $_GET["id"];

    $delete_query = "DELETE FROM ".$tablename." WHERE id=:id";
    $submit_stmt = $conn->prepare($delete_query);
    $submit_stmt->bindValue(':id', $id);
    if (!$submit_stmt) {
        header('Location: ../pages/error.php?error=Error while executing the query');
    }
    $submit_stmt->execute();
     if($_GET['db']==$MainDB)
         $dbc=1;
     else if($_GET['db']==$formDB)
	    $dbc=2;
	else
		$dbc=3;
    logActivity($_SESSION['uname'], 'Removed column for [id=' . $id . '] in [table=' . $tablename . '] of [db=' . $_GET['db'] . ']');
	header('Location: ../db-manage.php?db='.$dbc.'&table='.$tablename);
?>