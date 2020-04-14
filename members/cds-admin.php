<html>
<?php 
error_reporting(E_ALL & ~E_NOTICE);
include("header.php");
/* Directory Path Variables START */
$Generated_Certificate = '../public/Generated Certificate/';
$Uploaded_Files = "CDS_Admin/Uploaded files/";
$Certificate_Template_Path = "CDS_Admin/Certificate Templates/";
$Fonts_Path = "CDS_Admin/Fonts/";
/* Directory Path Variables END */
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <?php
        if (isset($_POST["submit"])) {
            echo "<title>Generating</title>";
        }
        else{
            echo "<title>CDS Admin: SVCE-ACM CMS</title>";
        }
    ?>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top" >
    <div id="wrapper">
     <?php include("navigation.php"); ?>            
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
                    <select class="form-control border-1 small" style="width: 68%;max-width:15em;" name ="eventType" required>
                        <option value = "0">Intra-College Event</option>
                        <option value = "1">Inter-College Event</option>
                    </select>
                    <br>
                    <select class="form-control border-1 small" style="width: 68%;max-width:15em;" name ="Type" required>
                        <option value = "Participation">Participaion Certificates</option>
                        <option value = "Winner">Winner Certificates</option>
                        <option value = "Runner">Runner Certificates</option>
                    </select>
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
                                    <?php
                                        if($_POST["eventType"]==0) {
                                            echo "<th>Registration Number</th>";
                                        }
                                        else {
                                            echo "<th>College</th>";
                                        }
                                   ?>
                                    <th>Position</th>	
                                    <th>Event Name</th>	
                                    <th>Certificate Link</th>
                                </thead>
                                <tbody>
<?php
	if (isset($_POST["submit"])){
        $Certificate_Template = $Certificate_Template_Path.$_POST["Type"].".png";
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
	            $department = str_replace("'"," ",ucwords($data[2]));
	            $year = $data[3];
	            $section = str_replace("'"," ",ucwords($data[4]));
	            $position = str_replace("'"," ",ucwords($data[5]));
	            $event_name = str_replace("'"," ",ucwords($data[6]));
	            $email = ucwords($data[7]);
	            /* Event variables END*/
                if($_POST["eventType"]==0){
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
                }
    	       else{
                    $im = imagecreatefrompng($Certificate_Template);
                    $college = ucwords($data[8]);
                    $sentence = "of ".$college." for participating in ".$event_name_main." conducted by SVCE ACM Student Chapter from ".$date_main;
                    $words = explode(" ",$sentence);
                    $sentences = array("","","","");
                    $no_of_sentences = 3;
                    $sent=0;
                    $line_limit =60;
                    $x =0 ;
                    while($x<sizeof($words)){
                        $letters = strlen($words[$x]) ;
                        while($letters<$line_limit){
                            $sentences[$sent] = $sentences[$sent]." ".$words[$x];
                            $x+=1;
                            $letters += strlen($words[$x]) +1;
                        }
                        $sent+=1;
                    }
                    imagettftext($im, 50, 0, 206, 1019, 0x535453, realpath($font_light), "This certificate is presented to");
                    if(strlen($participant_name)>22){
                        imagettftext($im, 100-((strlen($participant_name)-22)*7), 0, 206, 1185, 0x535453, realpath($font_medium), $participant_name);
                    }
                    else{
                        imagettftext($im, 100, 0, 206, 1185, 0x535453, realpath($font_medium), $participant_name);
                    }
                    imagettftext($im, 50, 0, 206, 1317,0x535453, realpath($font_regular), $sentences[0]);
                    imagettftext($im, 50, 0, 206, 1439, 0x535453, realpath($font_regular), $sentences[1]);
                    imagettftext($im, 50, 0, 206, 1562, 0x535453, realpath($font_regular), $sentences[2]);
                    imagettftext($im, 50, 0, 206, 1692, 0x535453, realpath($font_regular), $sentences[3]);
                    imagepng($im, $Generated_Certificate . $foldername[0] . '/Certificate-' . str_replace(" ","_",$event_name) . "_" . str_replace(" ","_",$participant_name) . "_" . $participant_id . '.png');
                    $cert_link = $Generated_Certificate . $foldername[0] . '/Certificate-' . str_replace(" ","_",$event_name) . "_" . str_replace(" ","_",$participant_name) . "_" . $participant_id . '.png';
                    imagedestroy($im);
                    $participant_id++;
                    $submit_sql = "INSERT INTO `certificates` (`name`,`regno`,`dept`,`year`,`section`,`position`,`cert_link`,`event_name`,`email`,`college`) VALUES (\"" . $participant_name . "\",\"" . $registration_number . "\",\"" . $department . "\",\"" . $year . "\",\"" . $section . "\",\"" . $position . "\",\"" . $cert_link . "\",\"" . $event_name . "\",\"".$email."\",\"".$college."\");";
                    $submit_stmt = $conn->prepare($submit_sql);
                    if (!$submit_stmt) {
                        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error . "<br>";
                    }
                    $submit_stmt->execute();
               }
                if($_POST["eventType"]==0){	            
                    echo '<tr><td>' . $participant_id . ' </td><td> ' . $participant_name . '</td><td> ' . $registration_number . '</td><td> ' . $position . '</td><td> ' . $event_name . '</td><td> <a href="' . $cert_link . '">Link</a></td></tr>';
                }	            
                else {
                    echo '<tr><td>' . $participant_id . ' </td><td> ' . $participant_name . '</td><td> ' . $college . '</td><td> ' . $position . '</td><td> ' . $event_name . '</td><td> <a href="' . $cert_link . '">Link</a></td></tr>';
                }                
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