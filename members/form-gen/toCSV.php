<?php   
    $event = $_GET['event']; 
    //"event_distrupt_2020";
    include("../header.php");
    $conn = new mysqli($servername, $username, $password, $formDB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
     //Create our SQL query.
     $sql = "SELECT * FROM ".$event." ORDER BY id";
       
     //Fetch all of the rows from our MySQL table.
     $rows =$conn->query($sql);
     
     //Get the column names.
     $columnNames = array();
     if(!empty($rows)){
          //We only need to loop through the first row of our result
          //in order to collate the column names.
        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$formDB."' AND `TABLE_NAME`='".$event."'";
        $columns = $conn->query($sql); // COLUMN_NAME
        $i=0;   
        foreach ($columns as $row) {
            $columnNames[$i]=$row['COLUMN_NAME'];
            $i++;
        }
     }
     
     //Setup the filename that our CSV will have when it is downloaded.
     $fileName = $event.'.csv';
     
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
?>