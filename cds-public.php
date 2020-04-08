<?php
    if (!empty( $_GET)) {
        // Runtime Variables
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "svcehost_cms";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if(isset($_GET['mode'])){
            $mode=1;
            $countr = -1;
            $event = "Events Details";
            $sql = "select event_name, date from events order by date;";
            $result = $conn->query($sql);
        }
        else{
            $mode=0;
            $event = strtolower($_GET['event']);
            // Call Procedures
            $sql = "select count(1) as code from events where event_name=\"".$event."\"";
            $result = $conn->query($sql);
            foreach ($result as $row) { 
                if ($row["code"]==0) {
                    header('Location: index.php?status=notfound');
                }
            }
            $sql = "SELECT name,regno,dept,year,section,position,cert_link from certificates where event_name=\"".$event."\" order by name";
            $result = $conn->query($sql);
            $resultc = $conn->query("SELECT COUNT(*) FROM certificates where event_name=\"".$event."\"");
            $rowc = $resultc->fetch_row();
            $countr = $rowc[0];
            // echo $countr; 
        }
    }
    else {
        header('Location: index.php?status=notfound');
    }
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Certificate Distribution System (CDS): SVCE-ACM</title>
    <link rel="icon" type="image/png" sizes="600x600" href="assets/img/Logo_White.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><img class="logo" src="assets/img/Logo_Banner_White.png"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="index.php" style="padding-top: 20px;"><i class="fas fa-award"></i><span>CDS</span></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="member-login.php"><i class="far fa-user-circle"></i><span>Member Login</span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form action="cds-public.php" method="GET" class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" name="event" placeholder="Search for Event">
                                <div class="input-group-append"><button class="btn btn-primary py-0" type="submit"><i class="fas fa-search"></i></button></div>
                            </div>
                        </form>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-right p-3 animated--grow-in" role="menu" aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small" type="text" name="event" placeholder="Search for Event">
                                            <div class="input-group-append"><button class="btn btn-primary py-0" type="submit"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <!--<li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="badge badge-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in"
                                        role="menu">
                                        <h6 class="dropdown-header">alerts center</h6>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 12, 2019</span>
                                                <p>A new monthly report is ready to download!</p>
                                            </div>
                                        </a>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 7, 2019</span>
                                                <p>$290.29 has been deposited into your account!</p>
                                            </div>
                                        </a>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 2, 2019</span>
                                                <p>Spending Alert: We've noticed unusually high spending for your account.</p>
                                            </div>
                                        </a><a class="text-center dropdown-item small text-gray-500" href="#">Show All Alerts</a></div>
                                </div>
                            </li>!-->
                            <li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow" role="presentation">
                                <!-- <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Username</span><img class="border rounded-circle img-profile" src="assets/img/avatars/avatar1.jpeg"></a>
                                    <div
                                        class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu"><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Settings</a>
                                        <a
                                            class="dropdown-item" role="presentation" href="#"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Activity log</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
                    </div> -->
                    </li>
                    </ul>
            </div>
            </nav>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Certificates Distrubution System (CDS)</h3>
                <?php
                    if (($countr == 0) && ($mode == 0)) { 
                        echo "<div class=\"card shadow mb-4\">
                            <div class=\"card-header py-3\">
                                <h6 class=\"text-primary m-0 font-weight-bold\">".ucwords($event)."</h6>
                            </div>
                            <div class=\"card-body\">
                                <p class=\"m-0\">Oops! You've found an event with no available certificates.<br>If you think this is a mistake, reach out to your closest SVCE-ACM Bros or mail us to <a href=\"mailto:acm.svcecse@gmail.com\">acm.svcecse@gmail.com</a> </p>
                            </div>
                            </div>";
                    }
                ?>
                <div class="card shadow" <?php if (($countr == 0) && ($mode == 0)) { echo "style=\"display: none;\""; } ?> >
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold"><?php echo ucwords($event); ?></p>
                    </div>
                    <div class="card-body">
                        <!--<div class="row">
                            <div class="col-md-6 text-nowrap">
                                <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label>Show&nbsp;<select class="form-control form-control-sm custom-select custom-select-sm"><option value="10" selected="">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select>&nbsp;</label></div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-right dataTables_filter" id="dataTable_filter"><label><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>
                            </div>
                        </div>!-->
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                            <table class="table dataTable my-0" id="dataTable">
                                <thead>
                                    <tr>
                                    <?php
                                        if($mode==0) {
                                            echo "
                                            <th>Name</th>
                                            <th>Registration Number</th>
                                            <th>Department</th>
                                            <th>Year</th>
                                            <th>Section</th>
                                            <th>Position</th>
                                            <th>Link</th>";
                                        }
                                        else {
                                            echo "
                                            <th>Event Name</th>
                                            <th>Conducted On</th>";
                                        }
                                    ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($mode==0) {
                                            foreach ($result as $row) {
                                                echo "<tr>";
                                                echo "    <td>".ucwords($row["name"])."<br></td>";
                                                echo "    <td>".$row["regno"]."</td>";
                                                echo "    <td>".ucwords($row["dept"])."</td>";
                                                echo "    <td>".$row["year"]."</td>";
                                                echo "    <td>".ucwords($row["section"])."</td>";
                                                echo "    <td>".ucwords($row["position"])."</td>";
                                                echo "    <td><a href=\"".$row["cert_link"]."\">Here</a></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
                                                foreach ($result as $row) {
                                                    echo "<tr>";
                                                    echo "    <td>".ucwords($row["event_name"])."</td>";
                                                    echo "    <td>".$row["date"]."</td>";
                                                    echo "</tr>";
                                                }
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <?php
                                        if($mode==0) {
                                            echo "
                                            <th>Name</th>
                                            <th>Registration Number</th>
                                            <th>Department</th>
                                            <th>Year</th>
                                            <th>Section</th>
                                            <th>Position</th>
                                            <th>Link</th>";
                                        }
                                        else {
                                            echo "
                                            <th>Event Name</th>
                                            <th>Conducted On</th>";
                                        }
                                    ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-md-6 align-self-center">
                                <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite"></p>
                            </div>
                            <div class="col-md-6">
                                <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                        <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                        !-->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>