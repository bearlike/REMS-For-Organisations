<?php
$event = $_GET['event'];
//"event_distrupt_2020";
include("../header.php");
try{
    $conn =  new PDO('mysql:dbname='.$formDB.';host='.$servername.';charset=utf8', $username, $password);
}catch(PDOException $e){
    $message = $e->getMessage()  ;
    header('pages/error.php?error='.$e->getMessage());
    die();
}

$sql = $conn->prepare("show TABLES from ".$formDB);
$sql->execute();
$tableNames = $sql->fetchAll(PDO::FETCH_ASSOC);
$table_list=[];

foreach ($tableNames as $row) {
    $table_list[]=$row["Tables_in_".$formDB];
}
if(in_array($event,$table_list)){
    //Create our SQL query.
    $sql = $conn->prepare("SELECT * FROM " . $event . " ORDER BY id");
    $sql->execute();
    //Fetch all of the rows from our MySQL table.
    $rows = $sql->fetchAll(PDO::FETCH_ASSOC);

    //Get the column names.
    $columnNames = array();
    if (!empty($rows)) {
        //We only need to loop through the first row of our result
        //in order to collate the column names.
        $sql = $conn->prepare("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`=:db AND `TABLE_NAME`=:table_name");
        $sql->bindValue(":db",$formDB);
        $sql->bindValue(":table_name",$event);
        $sql->execute();
        $columns = $sql->fetchAll(PDO::FETCH_ASSOC); // COLUMN_NAME
        $i = 0;
        foreach ($columns as $row) {
            $columnNames[$i] = $row['COLUMN_NAME'];
            $i++;
        }
    }

    //Setup the filename that our CSV will have when it is downloaded.
    $fileName = $event . '.csv';

    //Set the Content-Type and Content-Disposition headers to force the download.
    header('Content-Type: application/excel');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');

    //Open up a file pointer
    $fp = fopen('php://output', 'w');

    //Start off by writing the column names to the file.
    fputcsv($fp, $columnNames);

    //Then, loop through the rows and write them to the CSV file.
    foreach ($rows as $row) {
        fputcsv($fp, $row);
    }

    //Close the file pointer.
    fclose($fp);
    logActivity($_SESSION['uname'], 'Download table CSV for [table=' . $event . '], [db=' . $formDB . ']');
}
else{
    header('Location: ../../public/bad-request.html');
}

?>
