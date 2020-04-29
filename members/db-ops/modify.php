<?php
    include("../header.php");
    if(empty($_POST)){
        $table = $_GET["tbname"];
        $db = $_GET["db"];
        $id = $_GET["id"];
        $conn = new mysqli($servername, $username, $password, $db);
        // Check connection
        if ($conn->connect_error) {
            header('Location: ../pages/error.php?error='.$conn->connect_error);
        }
        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$db."' AND `TABLE_NAME`='".$table."'";
        // echo $sql;   // For testing
        $columns = $conn->query($sql); // COLUMN_NAME
        $i=0;
        foreach ($columns as $row) {
            $colArr[$i]=$row['COLUMN_NAME'];
            $i++;
        }
        $sql = "select * from ".$table." where id=".$id.";";
        $temp  = $conn->query($sql);
        $colSelect = $temp->fetch_row();
        $counterVar=0;
    }
    else{
        $db = $_POST["db"];
        $table = $_POST["table"];
        $id = $_POST["id"];
        $conn = new mysqli($servername, $username, $password, $db);
        // Check connection
        if ($conn->connect_error) {
            header('Location: ../pages/error.php?error='.$conn->connect_error);
        }
        $updateSQL="UPDATE ".$table." SET id=".$id;
        foreach ($_POST as $key => $value) {
            if(($key!="id")&&($key!="submit")&&($key!="table")&&($key!="db"))
                $updateSQL=$updateSQL.", ".$key."="."\"".$value."\"";
        }
        $updateSQL=$updateSQL." WHERE id=".$id;
        echo $updateSQL."<br><br>";
        if($db==$dbname)
            $dbc=1;
        else
            $dbc=2;
        if ($conn->query($updateSQL) === TRUE) {
            echo "Record updated successfully";
            logActivity($_SESSION['uname'], 'Modified column for [id=' . $id . '] in [table=' . $table . '] of [db=' . $db . ']');
            header('Location: ../db-manage.php?db='.$dbc.'&table='.$table);
        }
        else {
            header('Location: ../pages/error.php?error='.$conn->error);
        }
    }
?>
<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Modify Row: SVCE-ACM</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="../../assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
		<?php include("../navigation.php"); ?>
        <script src="../../assets/js/dark-mode.js"></script>
		<div class="container-fluid">
			<div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold">Modify Row in <?php echo $db.".".$table;   ?></p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					    <table class="table dataTable table-sm" id="dataTable">
							<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" style="width: 60%;margin-left: 20%;margin-right: 20%" onSubmit="return validate_form();">
                                    <?php
                                        foreach ($colArr as $col){
                                        	if($col!='id' && $col!='timestamp'){
                                                echo '<tr>';
                                                echo '<th>'.$col.'</th>';
                                                echo '<td><input type="text" value="'.$colSelect[$counterVar].'" name="'.$col.'" class="form-control" required></td>';
                                                echo '<td></td>';
	                                         	echo '</tr>';
                                             }
                                        	else{
                                                echo '<tr>';
                                                echo '<th>'.$col.'</th>';
                                                echo '<td><input type="text" value="'.$colSelect[$counterVar].'" class="form-control" readonly></td>';
                                                echo '<td></td>';
	                                         	echo '</tr>';
                                            }
                                            $counterVar++;
                                        }
                                    ?>
								<tr>
									<td>
                                        <input type="hidden" name="db" value="<?php echo $db;  ?>" ?>
                                        <input type="hidden" name="table" value="<?php echo $table;  ?>" ?>
                                        <input type="hidden" name="id" value="<?php echo $id;  ?>" ?>
										<input type="submit" style="margin-top: 1em;" value="Modify Values" name="submit" class="btn btn-md btn-primary mb-2">
									</td>
									<td></td>
									<td></td>
								</tr>
							</form>
						</table>
					</div>
                    </div>
			</div>
			<br><br>
		</div>
	</div>
     <footer class="bg-white sticky-footer">
     	<div class="container my-auto">
               <div class="text-center my-auto copyright"><span>SVCE ACM Student Chapter</span></div>
          </div>
     </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
	<script src="../../assets/js/bs-init.js"></script>
	<script src="../../assets/js/theme.js"></script>
</body>
</html>
