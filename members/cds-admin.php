<html>
<?php 
include("header.php");
/* Directory Path Variables START */
$Generated_Certificate = '../public/Generated Certificate/';
$Uploaded_Files = "CDS_Admin/Uploaded files/";
$Certificate_Template = "CDS_Admin/Certificate Templates/Empty Certificate.png";
$Fonts_Path = "CDS_Admin/Fonts/";
/* Directory Path Variables END */
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>CDS Admin: SVCE-ACM CMS</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top" >
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon"><img class="logo" src="../assets/img/Logo_Banner_White.png"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item" role="presentation"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="cds-admin.php"><i class="fab fa-creative-commons-share"></i><span>Certificate Generation<br></span></a></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                            </div>
                        </form>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-search"></i></a>
                                <div class="dropdown-menu dropdown-menu-right p-3 animated--grow-in" role="menu" aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto navbar-search w-100">
                                        <div class="input-group"><input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                            <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="badge badge-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in"
                                        role="menu">
                                        <h6 class="dropdown-header">alerts center</h6>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 12, 2019</span>
                                                <p>Sample Text</p>
                                            </div>
                                        </a>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 7, 2019</span>
                                                <p>Sample Text</p>
                                            </div>
                                        </a>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
                                            </div>
                                            <div><span class="small text-gray-500">December 2, 2019</span>
                                                <p>Sample Text</p>
                                            </div>
                                        </a><a class="text-center dropdown-item small text-gray-500" href="#">Show All Alerts</a></div>
                                </div>
                            </li>
                            <li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
                            </li>
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow" role="presentation">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small"><?php echo $loginUser; ?></span><img class="border rounded-circle img-profile" src="../assets/img/avatars/avatar1.jpeg"></a>
                                    <div
                                        class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu"><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Settings</a>
                                        <a
                                            class="dropdown-item" role="presentation" href="#"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Activity log</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
                    </div>
                    </li>
                    </ul>
            </div>
            </nav>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Certificate Generation</h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="CDS_Admin/Sample_headers.csv"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Download Sample CSV for CDS&nbsp;</a></div>
                <style>
                .upload-btn-wrapper input[type=file] {
                    opacity: 0;
                }
                </style>
                <div class="" style="padding-bottom: 19px;">
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                    <input type="file" name="file" id="file" required />
                    <br><br>
                    <input type="text" name="event_name" class="form-control border-1 small" style="width: 68%;max-width:15em;" placeholder="Enter the Event Name" required />
                    <br>
                    <input type="text" name="date" class="form-control border-1 small" style="width: 68%;max-width:15em;" placeholder="Enter the Date of the event" required />
                    <br> 
                    <input class="btn btn-primary" type="submit" name="submit" />
                </form>
                </div>

                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold">Metadata</p>
                    </div>
                    <div class="card-body">                   
<?php
    /* Driver */
    if (isset($_POST["submit"])) {
        if (isset($_FILES["file"])) {
            if ($_FILES["file"]["error"] > 0) {
                echo "<b>Return Code</b>: " . $_FILES["file"]["error"] . "<br />";
            } 
            else {
                echo "<b>Upload</b>: " . $_FILES["file"]["name"] . "<br />";
                echo "<b>Type</b>: " . $_FILES["file"]["type"] . "<br />";
                echo "<b>Size</b>: " . round (($_FILES["file"]["size"] / 1024),2) . " Kb<br />";
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " already exists. ";
                } 
                else {
                    $storagename = $_FILES["file"]["name"];
                    move_uploaded_file($_FILES["file"]["tmp_name"], $Uploaded_Files . $storagename);
                    // echo "<b>Stored in</b>: " . "Uploaded files/" . $_FILES["file"]["name"] . "<br />";
                }
            }
        } 
        else {
            echo "No file selected <br />";
        }
    }
    echo "<br>";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error); // IF-Fail to Connect
    }
    if (isset($_FILES["file"])){
        $foldername = explode('.', $_FILES["file"]["name"]);
        // echo $foldername[0];
        if (!file_exists($Generated_Certificate . $foldername[0])) {
            mkdir($Generated_Certificate . $foldername[0], 0777, true);
        }
    }
    $row = 1;
    $flag = true;
    $participant_id = 0000;
?>
                    </div>
                </div>
                <br><br>
                <div class="card shadow">
                    <div class="card-header py-3">
                        <p class="text-primary m-0 font-weight-bold">Certification Creation Log</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                            <table class="table dataTable my-0" id="dataTable">
                                <thead>
                                    <th>ID</th>	
                                    <th>Name</th>	
                                    <th>Registration Number</th>	
                                    <th>Position</th>	
                                    <th>Event Name</th>	
                                    <th>Certificate Link</th>
                                </thead>
                                <tbody>
<?php
	if (isset($_POST["submit"])){
		$im_template = imagecreatefrompng($Certificate_Template);
		$font_light = $Fonts_Path.'Raleway/Raleway-Light.ttf';
		$font_regular = $Fonts_Path.'Raleway/Raleway-Regular.ttf';
		$font_semibold = $Fonts_Path.'Raleway/Raleway-SemiBold.ttf';
		$font_medium =$Fonts_Path.'Raleway/Raleway-Medium.ttf';
		$event_name_main = $_POST["event_name"];
		$date_main = $_POST["date"];
		imagettftext($im_template, 50, 0, 206, 1019, 0x535453, realpath($font_light), "This certificate is presented to");
		imagettftext($im_template, 50, 0, 206, 1317,0x535453, realpath($font_light), "For participating in");
		imagettftext($im_template, 60, 0, 206, 1439, 0x535453, realpath($font_medium), $event_name_main);
		imagettftext($im_template, 55, 0, 206, 1559, 0x535453, realpath($font_light), "Conducted by");
		imagettftext($im_template, 55, 0, 706, 1562, 0x535453, realpath($font_medium), "SVCE ACM Student Chapter");
		imagettftext($im_template, 55, 0, 206, 1672, 0x535453, realpath($font_light), "on");
		imagettftext($im_template, 55, 0, 315, 1672, 0x535453, realpath($font_medium), $date_main);
		$generated_template =  $Generated_Certificate . $foldername[0] . '/template.png';
		imagepng($im_template,$generated_template);
		imagedestroy($im_template);

	    if (isset($storagename) && $handle = fopen($Uploaded_Files . $_FILES["file"]["name"], "r")) {
	        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	            if ($flag) {
	                $flag = false;
	                continue;
	            }
	            /* Event variables START*/
	            $participant_name = ucwords($data[0]);
	            $registration_number = $data[1];
	            $department = ucwords($data[2]);
	            $year = $data[3];
	            $section = ucwords($data[4]);
	            $position = ucwords($data[5]);
	            $event_name = ucwords($data[6]);
	            $email = ucwords($data[7]);
	            /* Event variables END*/
	            $im = imagecreatefrompng($generated_template);
	           if(strlen($participant_name)>22){
				imagettftext($im, 100-((strlen($name)-24)*7), 0, 206, 1185, 0x535453, realpath($font_medium), $participant_name);
				}
				else{
					imagettftext($im, 100, 0, 206, 1185, 0x535453, realpath($font_medium), $participant_name);
				}
	            imagepng($im, $Generated_Certificate . $foldername[0] . '/Certificate-' . str_replace(" ","_",$event_name) . "_" . str_replace(" ","_",$participant_name) . "_" . $participant_id . '.png');
	            $cert_link = $Generated_Certificate . $foldername[0] . '/Certificate-' . str_replace(" ","_",$event_name) . "_" . str_replace(" ","_",$participant_name) . "_" . $participant_id . '.png';
	            imagedestroy($im);
	            $participant_id++;
	            $submit_sql = "INSERT INTO `certificates` (`name`,`regno`,`dept`,`year`,`section`,`position`,`cert_link`,`event_name`,`email`) VALUES ('" . $participant_name . "','" . $registration_number . "','" . $department . "','" . $year . "','" . $section . "','" . $position . "','" . $cert_link . "','" . $event_name . "','".$email."');";
	            $submit_stmt = $conn->prepare($submit_sql);
	            if (!$submit_stmt) {
	                echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
	            }
	            $submit_stmt->execute();
	            echo '<tr><td>' . $participant_id . ' </td><td> ' . $participant_name . '</td><td> ' . $registration_number . '</td><td> ' . $position . '</td><td> ' . $event_name . '</td><td> <a href="' . $cert_link . '">Link</a></td></tr>';
	            $submit_stmt->close();
	        }
	        fclose($handle);
	    }
	}
?>
                                </tbody>
                            </table>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>