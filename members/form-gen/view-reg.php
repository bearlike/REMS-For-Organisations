<?php
    include("../header.php");
	// Dictionary on what to replace what with what
	$display_prompts=array(
                        'id' => 'ID',
                        'timestamp' => 'Timestamp',
                        'participant_name' => 'Particpant Name',
                        'participant_name1' => 'Particpant Name #1',
                        'participant_name2' => 'Particpant Name #2',
                        'participant_name3' => 'Particpant Name #3',
                        'participant_name4' => 'Particpant Name #4',
                        'participant_name5' => 'Particpant Name #5',
						'regno' => 'Registration Number' ,
						'dept' => 'Department',
						'year' => 'Year',
						'college' => 'College Name',
						'github' => 'GitHub Profile link',
						'email' => 'Email Address',
						'phoneno' => 'Contact number',
						'linkedin' => 'LinkedIn Profile URL',
						'regno1' => 'Registration Number #1' ,
						'dept1' => 'Department #1',
						'year1' => 'Year #1',
						'college1' => 'College #1',
						'github1' => 'GitHub #1',
						'email1' => 'Email #1',
						'phoneno1' => 'Phone #1',
						'linkedin1' => 'LinkedIn #1',
						'regno2' => 'Registration Number #2' ,
						'dept2' => 'Department #2',
						'year2' => 'Year #2',
						'college2' => 'College #2',
						'github2' => 'GitHub #2',
						'email2' => 'Email #2',
						'phoneno2' => 'Phone #2',
						'linkedin2' => 'LinkedIn #2',
						'regno3' => 'Registration Number #3' ,
						'dept3' => 'Department #3',
						'year3' => 'Year #3',
						'college3' => 'College #3',
						'github3' => 'GitHub #3',
						'email3' => 'Email #3',
						'phoneno3' => 'Phone #3',
						'linkedin3' => 'LinkedIn #3',
						'regno4' => 'Registration Number #4' ,
						'dept4' => 'Department #4',
						'year4' => 'Year #4',
						'college4' => 'College #4',
						'github4' => 'GitHub #4',
						'email4' => 'Email #4',
						'phoneno4' => 'Phone #4',
						'linkedin4' => 'LinkedIn #4',
						'regno5' => 'Registration Number #5' ,
						'dept5' => 'Department #5',
						'year5' => 'Year #5',
						'college5' => 'College #5',
						'github5' => 'GitHub #5',
						'email5' => 'Email #5',
						'phoneno5' => 'Phone #5',
                        'linkedin5' => 'LinkedIn #5',
    );
    $event="Choose an event";
    //$_GET['event'] = "event_design_jam_2020";
    $page=1;
    $perPage=10;;
    $totalPages=1;
    if(empty($_GET['search'])){
        $_GET['search']="";
    }
    if(empty($_GET['page'])){
        $page=1;
    }
    else{
        $page=$_GET['page'];
    }
    if(empty($_GET['perPage'])){
        $perPage=10;
    }
    else{
        $perPage=$_GET['perPage'];
    }
    $conn = new mysqli($servername, $username, $password, $formDB);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "show TABLES from ".$formDB." WHERE Tables_in_".$formDB." LIKE 'event_%';";
    $eventNames = $conn->query($sql);
    if(!(empty($_GET['event']))){
        $event = $_GET['event'];
        $sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$formDB."' AND `TABLE_NAME`='".$event."'";
        $columns = $conn->query($sql); // COLUMN_NAME
        $i=0;
        foreach ($columns as $row) {
            $colArr[$i]=$row['COLUMN_NAME'];
            $i++;
        }
        $resultc = $conn->query("select count(*) from ".$event.";");
        $rowc = $resultc->fetch_row();
        $countr = $rowc[0]; // Count total responses
        // calculate number of pages needed
        $totalPages = ceil($countr/$perPage);
        // Find the starting element for the current $page
        $startPage = $perPage*($page-1);
        $sql = "select * from ".$event." order by id limit ".$startPage.",".$perPage.";";
        $registrants  = $conn->query($sql);
    }
?>
<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>View Registrants: SVCE-ACM</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../../assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
            <?php include("../navigation.php"); ?>
            <script src="../../assets/js/dark-mode.js"></script>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">View Responses</h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="<?php if(isset($_GET['event'])){echo "toCSV.php?event=".$event."\" target=\"_blank\"";}else{echo "#";}  ?>"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Download CSV&nbsp;</a></div>
                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
                            <select onchange="this.form.submit()" class="form-control border-1 small" style="width: 68%;max-width:15em;" name ="event" required>
                                <option value = "">Select an event</option>
                                <?php
                                    foreach ($eventNames as $row) {
                                        echo "<option value = ".$row["Tables_in_".$formDB].">".ucwords(str_replace("event ","",(str_replace("_"," ",$row["Tables_in_".$formDB]))))."</option>";
                                    }
                                ?>
                            </select><br>
                        </form>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4"></div>
                    <div class="col-md-6 col-xl-3 mb-4"></div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow border-left-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Total Responses</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php if(isset($countr)){echo $countr;}else{echo "No event chosen";}  ?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-friends fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold"><?php echo ucwords(str_replace("event ","",(str_replace("_"," ",$event)))); ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-nowrap">
                                <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label>Show&nbsp;
                                    <!-- Form weirdly starts here, don't ask me why :3 !-->
                                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>"  method="GET">
                                        <input type="hidden" name="event" value="<?php echo $event; ?>"/>
                                        <input type="hidden" name="page"  value="<?php echo $page;  ?>"/>
                                        <select onchange="this.form.submit()" name="perPage" class="form-control form-control-sm custom-select custom-select-sm">
                                            <option value="10" <?php if($perPage==10){echo 'selected=""';} ?>>10</option>
                                            <option value="25" <?php if($perPage==25){echo 'selected=""';} ?>>25</option>
                                            <option value="50" <?php if($perPage==50){echo 'selected=""';} ?>>50</option>
                                            <option value="100" <?php if($perPage==100){echo 'selected=""';} ?>>100</option>
                                        </select>&nbsp;</label></div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-md-right dataTables_filter" id="dataTable_filter"><label><input type="search" name="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search Name"></label></div></form>
                                        </div>
                                    </div>
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                            <table class="table dataTable my-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <?php
                                            if(!(empty($_GET['event']))){
                                                foreach ($colArr as $col){
                                                    echo "<th>".$display_prompts[$col]."</th>";
                                                }
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                         if(!(empty($_GET['event']))){
                                            foreach ($registrants as $row){
                                                echo "<tr>";
                                                foreach ($colArr as $col){
                                                    echo "<td>".ucwords($row[$col])."</td>";
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <?php
                                            if(!(empty($_GET['event']))){
                                                foreach ($colArr as $col){
                                                    echo "<th>".$display_prompts[$col]."</th>";
                                                }
                                            }
                                        ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite"></p>
                            </div>
                            <form class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                        <li class="page-item <?php if($page==1){echo 'disabled';} ?>"><button name="page" value="<?php echo ($page-1); ?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></button></li>
                                        <input type="hidden" name="event" value="<?php echo $_GET['event']; ?>"/>
                                        <input type="hidden" name="perPage" value="<?php echo $perPage; ?>"/>
                                        <?php
                                            // Generate buttons for choosing pages
                                            for ($x=1; $x<=$totalPages; $x++){
                                                if($x==$page){
                                                    echo '<li class="page-item active"><button name="page" value="'.$x.'" class="page-link">'.$x.'</button></li>';
                                                }
                                                else{
                                                    echo '<li class="page-item"><button name="page" value="'.$x.'" class="page-link">'.$x.'</button></li>';
                                                }
                                             }
                                        ?>

                                        <li class="page-item <?php if($page==$totalPages){echo 'disabled';} ?>"><button name="page" value="<?php echo ($page+1); ?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span></button></li>
                                    </ul>
                                </nav>
                            </form>
                        </div>

                    </div>
                </div>
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
