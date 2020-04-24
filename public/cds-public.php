<?php
    if (!empty( $_GET)) {
        // Include Public Headers
        include("pheader.php");
        // Create connection
        $conn = new mysqli($servername, $username, $password, $MainDB);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // To know if user is searching for a name/event
        if(empty($_GET['search'])){
            $_GET['search']="";
        }
        // To know user's current page
        if(empty($_GET['page'])){
            $page=1;
        }
        else{
            $page=$_GET['page'];
        }
        // Number of tuples per page
        if(empty($_GET['perPage'])){
            $perPage=10;
        }
        else{
            $perPage=$_GET['perPage'];
        }
        // If user wants to see the events conducted and not the cert generated [$mode=1]
        if(isset($_GET['mode'])){
            $mode=1;
            $_GET['event']="";
            $countr = -1;
            $event = "Events Details";
            $resultc = $conn->query("SELECT COUNT(DISTINCT event_name, date) FROM events");
            $rowc = $resultc->fetch_row();
            $countr = $rowc[0]; // Count total certificates
            $totalPages = ceil($countr/$perPage);
            $startPage = $perPage*($page-1);
            $sql = "select event_name, date from events order by date limit ".$startPage.",".$perPage.";";
            $result = $conn->query($sql);
        }
        // If user wants to see the cert generated and not the events conducted  [$mode=0]
        else{
            $mode=0;
            $event = strtolower($_GET['event']);
            // To know if an event exist or not
            $sql = "select count(1) as code from events where event_name=\"".$event."\"";
            $result = $conn->query($sql);
            foreach ($result as $row) { 
                if ($row["code"]==0) {
                    header('Location: ../index.php?status=notfound');
                }
            }
            // To know if an event is inter or intra
            $sql = "select isInter as code from events where event_name=\"".$event."\"";
            $result = $conn->query($sql);
            foreach ($result as $row) { 
                if ($row["code"]==0)
                    $isInter=False;
                else
                    $isInter=True;
            }
            $resultc = $conn->query("SELECT COUNT(DISTINCT name,regno,college,dept,year,section,position,cert_link) FROM certificates where event_name=\"".$event."\" AND name LIKE '%".$_GET['search']."%' ");
            $rowc = $resultc->fetch_row();
            $countr = $rowc[0]; // Count total certificates
            // calculate number of pages needed
            $totalPages = ceil($countr/$perPage);
            // Find the starting element for the current $page
            $startPage = $perPage*($page-1);
            // SLECT for table
            if (!($isInter))
                $sql = "SELECT DISTINCT name,regno,dept,year,section,position,cert_link from certificates where event_name=\"".$event."\" AND name LIKE '%".$_GET['search']."%' order by name limit ".$startPage.",".$perPage.";";            
            else
                $sql = "SELECT DISTINCT name,college,year,position,cert_link from certificates where event_name=\"".$event."\" AND name LIKE '%".$_GET['search']."%' order by name limit ".$startPage.",".$perPage.";";            
            $result = $conn->query($sql);
            // echo $countr; 
        }
    }
    else {
        // Display event not found
        header('Location: ../index.php?status=notfound');
    }
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Certificate Distribution System (CDS):<?php echo " ".$OrgName; ?></title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="../src/fakeLoader.js-2.0.0/css/fakeLoader.css">
</head>

<body id="page-top">
    <div class="fakeLoader"></div>
    <div id="wrapper">
        <nav id="navbar" class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><img class="logo" src="../assets/img/Logo_Banner_White.png"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../index.php" style="padding-top: 20px;"><i class="fas fa-award"></i><span>CDS</span></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="../members/member-login.php"><i class="far fa-user-circle"></i><span>Member Login</span></a></li>
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
                            <li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow" role="presentation">
                                <!-- <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Username</span><img class="border rounded-circle img-profile" src="../assets/img/avatars/avatar1.jpeg"></a>
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
                                <p class=\"m-0\">";
                        if(empty($_GET['search']))
                            echo "Oops! You've found an event with no available certificates.<br>If you think this is a mistake, reach out to your closest SVCE-ACM Bros or mail us to <a href=\"mailto:acm.svcecse@gmail.com\">acm.svcecse@gmail.com</a>";
                        else
                            echo "Oops! We can't find what you've searched. Check the spelling and <a href=\"".$_SERVER["PHP_SELF"]."?event=".$_GET['event']."\">return back</a> to try again.<br>If you think this is a mistake, reach out to your closest SVCE-ACM Bros or mail us to <a href=\"mailto:acm.svcecse@gmail.com\">acm.svcecse@gmail.com</a>";
                        echo "</p></div></div>";
                    }
                ?>
                <div class="card shadow" <?php if (($countr == 0) && ($mode == 0)) { echo "style=\"display: none;\""; } ?> >
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold"><?php echo ucwords($event); ?></p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 text-nowrap">
                                <div id="dataTable_length" <?php if($mode==1){echo 'style="display: none;"';} ?> class="dataTables_length" aria-controls="dataTable"><label>Show&nbsp;
                                    <!-- Form weirdly starts here, don't ask me why :3 !-->
                                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>"  method="GET">
                                        <input type="hidden" name="event" value="<?php echo $_GET['event']; ?>"/>
                                        <input type="hidden" name="page" value="1"/>
                                        <?php
                                            if(isset($_GET['mode'])) 
                                                echo '<input type="hidden" name="mode" value=""/>';
                                        ?>
                                        <select onchange="this.form.submit()" name="perPage" class="form-control form-control-sm custom-select custom-select-sm">
                                            <option value="10" <?php if($perPage==10){echo 'selected=""';} ?>>10</option>
                                            <option value="25" <?php if($perPage==25){echo 'selected=""';} ?>>25</option>
                                            <option value="50" <?php if($perPage==50){echo 'selected=""';} ?>>50</option>
                                            <option value="100" <?php if($perPage==100){echo 'selected=""';} ?>>100</option>
                                        </select>&nbsp;</label></div>
                                        </div>
                                        <div <?php if($mode==1){echo 'style="display: none;"';} ?> class="col-md-6">
                                            <div class="text-md-right dataTables_filter" id="dataTable_filter"><label><input type="search" name="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search Name"></label></div></form>
                                        </div>
                                    </div>
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                            <table class="table dataTable my-0" id="dataTable">
                                <thead>
                                    <tr>
                                    <?php
                                        if($mode==0 && !($isInter)) {
                                            echo "
                                            <th>Name</th>
                                            <th>Registration Number</th>
                                            <th>Department</th>
                                            <th>Year</th>
                                            <th>Section</th>
                                            <th>Position</th>
                                            <th>Link</th>";
                                        }
                                        else if($mode==0 && $isInter) {
                                            echo "
                                            <th>Name</th>
                                            <th>College Name</th>
                                            <th>Year</th>
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
                                        if($mode==0 && !($isInter)) {
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
                                        else if($mode==0 && $isInter) {
                                            foreach ($result as $row) {
                                                echo "<tr>";
                                                echo "    <td>".ucwords($row["name"])."<br></td>";
                                                echo "    <td>".ucwords($row["college"])."</td>";
                                                echo "    <td>".$row["year"]."</td>";
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
                                        if($mode==0 && !($isInter)) {
                                            echo "
                                            <th>Name</th>
                                            <th>Registration Number</th>
                                            <th>Department</th>
                                            <th>Year</th>
                                            <th>Section</th>
                                            <th>Position</th>
                                            <th>Link</th>";
                                        }
                                        else if($mode==0 && $isInter) {
                                            echo "
                                            <th>Name</th>
                                            <th>College Name</th>
                                            <th>Year</th>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script src="../src/fakeLoader.js-2.0.0/js/fakeLoader.js"></script>
    <script>
        $(document).ready(function(){
            $.fakeLoader({
                timeToHide:1200,
                bgColor:"#4e73df",
                spinner:"spinner6"
            });
        });
        var isMobile = false; //initiate as false
        // device detection
        if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
            || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
            isMobile = true;
            document.getElementById('navbar').classList.add('toggled');
        }
        
    </script>

</body>

</html>