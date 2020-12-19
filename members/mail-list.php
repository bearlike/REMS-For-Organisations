<?php
include("header.php");
$Uploaded_Files = "Mailing-list/Uploaded files/";
try{
    $conn = new PDO('mysql:dbname='.$mailerDB.';host='.$servername.';charset=utf8', $username, $password);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    $message = $e->getMessage()  ;
    header('Location:pages/error.php?error='.$e->getMessage());
    die();
}
?>
<html>

<head id="head_tag">
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
     <title>Mailing List:<?php echo " ".$OrgName; ?></title>
     <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
     <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
     <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
     <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css">
     <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top">
     <div id="wrapper">
          <?php include("navigation.php"); ?>
          <div class="container-fluid">
               <style>
               .upload-btn-wrapper input[type=file] {
                    opacity: 0;
               }
               </style>
               <div class="d-sm-flex justify-content-between align-items-center mb-4">
               <h3 class="text-dark mb-1">Mailing List Generator</h3><a class="btn btn-primary btn-sm d-none d-sm-inline-block" role="button" href="Mailing-list/Sample_headers.csv"><i class="fas fa-download fa-sm text-white-50"></i>&nbsp;Download Sample CSV for Mailing list&nbsp;</a>
           </div>
               <div class="" style="padding-bottom: 30px; padding-top:30px">
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" enctype="multipart/form-data">
                        <div style="display:inline-flex">
                            <input type="file" id="myFile" name="file" style="display: none" required />
                                            <input id="spnFilePath" class="form-control border-1 small" style="width: 100%;max-width:15em;" placeholder="Selected Mailing list file" disabled>
                                           <a class="btn btn-primary btn-sm link" id="btnFileUpload"><i
                                                     class="fa fa-upload" aria-hidden="true"></i></a>
                            </div>
                                           <!-- <span id="spnFilePath"></span> -->
                                           <script type="text/javascript">
                                                // To hide the ugly file upload input and replace it with a button
                                                window.onload = function() {
                                                     var fileupload = document.getElementById("myFile");
                                                     var filePath = document.getElementById("spnFilePath");
                                                     var button = document.getElementById("btnFileUpload");
                                                     button.onclick = function() {
                                                          fileupload.click();
                                                     };
                                                     fileupload.onchange = function() {
                                                          var fileName = fileupload.value.split('\\')[fileupload.value
                                                               .split('\\').length - 1];
                                                          filePath.value =  fileName;
                                                     };
                                                };
                                           </script>
                         <br><br>
                         <input type="text" name="mailer_name" class="form-control border-1 small"
                              style="width: 68%;max-width:15em;" placeholder="Enter the Mailer Name" required />
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
                    $executed = false;
                    /* Driver */
                    if (isset($_POST["submit"])) {
                        echo "<title><head>Processing......</head></title>";
                        if (isset($_FILES["file"])) {
                            if ($_FILES["file"]["error"] > 0) {
                                echo "<b>Return Code</b>: " . $_FILES["file"]["error"] . "<br />";
                            } else {
                                echo "<b>Upload</b>: " . $_FILES["file"]["name"] . "<br />";
                                echo "<b>Type</b>: " . $_FILES["file"]["type"] . "<br />";
                                echo "<b>Size</b>: " . round(($_FILES["file"]["size"] / 1024), 2) . " Kb<br />";
                                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                                    echo $_FILES["file"]["name"] . " already exists. ";
                                    logActivity($_SESSION['uname'], 'In Mail-List, [' . $_FILES["file"]["name"] . '] already exists for Mail Listing [name='.$_POST["mailer_name"].']');
                                } else {
                                    $storagename = $_FILES["file"]["name"];
                                    move_uploaded_file($_FILES["file"]["tmp_name"], $Uploaded_Files . $storagename);
                                    // echo "<b>Stored in</b>: " . "Uploaded files/" . $_FILES["file"]["name"] . "<br />";
                                    logActivity($_SESSION['uname'], 'In Mail-List, [' . $_FILES["file"]["name"] . '] file uploaded for Mail Listing [name='.$_POST["mailer_name"].']');

                                }
                            }
                        } else {
                            echo "No file selected <br />";
                        }
                        //Create a table for this list
                        $table_name = str_replace(" ", "_", $_POST["mailer_name"]);

                            $creation_query = $conn->prepare("CREATE TABLE IF NOT EXISTS " . $table_name . " (id int PRIMARY KEY AUTO_INCREMENT NOT NULL,name VARCHAR(255),email VARCHAR(255));");
                            $creation_query->execute();

                            echo "<br>";
                            $flag = true;
                            if (isset($storagename) && $handle = fopen($Uploaded_Files . $_FILES["file"]["name"], "r")) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    if ($flag) {
                                        $flag = false;
                                        continue;
                                    }
                                    $name = ucwords($data[0]);
                                    $email = $data[1];
                                    $insert_query = $conn->prepare('INSERT INTO '.$table_name.' VALUES(NULL,:name,:email)');
                                    $insert_query->bindValue(':name', $name);
                                    $insert_query->bindValue(':email', $email);
                                    $insert_query->execute();
                                    $executed = true;
                                }
                            }

                    }


                    ?>


                    </div>
               </div>
               <?php
            if ($executed == true) {
                echo '<div class="alert alert-success" role="alert" style="margin-top:20px;">
                            Mailing list created!
                            </div>';
            }
            ?>
               <br><br>
          </div>
     </div>




     <footer class="bg-white sticky-footer">
          <div class="container my-auto">
               <div class="text-center">Made with ❤️ by <a href="https://thekrishna.in/">Krishnakanth</a> and <a href="https://mahav.me/">Mahalakshumi</a></div>
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
