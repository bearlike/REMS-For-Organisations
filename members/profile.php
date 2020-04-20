<?php include("header.php"); ?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "svcehost_cms";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
//profile pic upload
if(isset($_POST['photo_settings'])){
  $name = $_FILES['file']['name'];
  $target_dir = "../assets/img/avatars/";
  $target_file = $target_dir . basename($_FILES["file"]["name"]);

  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

  // Check extension
  if( in_array($imageFileType,$extensions_arr) ){

    // Convert to base64
    $image_base64 = base64_encode(file_get_contents($_FILES['file']['tmp_name']) );
    $image = 'data:image/'.$imageFileType.';base64,'.$image_base64;
    // Insert record
    $query = "UPDATE svcehost_cms.login SET imgsrc='$name', image='$image' where LoginName = '$loginUser'";
    mysqli_query($conn,$query);

    // Upload file
    move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
  }

}
//retrieve profile picture

  $sql1 = "select imgsrc from svcehost_cms.login where LoginName = '$loginUser'";
  $result1 = mysqli_query($conn,$sql1);
  $row1 = mysqli_fetch_array($result1);
  if($row1['imgsrc']==""){
    $image_src="../assets/img/avatars/image2.png";
  }else{
      $image = $row1['imgsrc'];
      $image_src = "../assets/img/avatars/".$image;
  }




//user details form
if(isset($_POST['user_settings'])){
  $email          =$_POST['email'];
  $firstname      =$_POST['first_name'];
  $lastname       =$_POST['last_name'];
  $query = "UPDATE svcehost_cms.login SET lastname='$lastname', FirstName='$firstname', Email='$email' where LoginName = '$loginUser'";

  if (!mysqli_query($conn, $query)) {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
  // contact details form
  if(isset($_POST['contact_settings'])){
  $address         =$_POST['address'];
  $phno            =$_POST['phno'];

  $query = "UPDATE svcehost_cms.login SET Address='$address', Phno='$phno' where LoginName = '$loginUser'";

  if (!mysqli_query($conn, $query)) {
    echo "Error updating record: " . mysqli_error($conn);
  }
}

$sql = "SELECT * from login where LoginName = '$loginUser'";
$result = mysqli_query($conn, $sql);

//signature details form
if(isset($_POST['signature_settings'])){
$signature         =$_POST['signature'];
$query = "UPDATE svcehost_cms.login SET Signature='$signature' where LoginName = '$loginUser'";

if (!mysqli_query($conn, $query)) {
  echo "Error updating record: " . mysqli_error($conn);
}
}

$sql = "SELECT * from login where LoginName = '$loginUser'";
$result = mysqli_query($conn, $sql);

?>

<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <title>Profile: SVCE-ACM CMS</title>
      <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
      <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css">
      <link rel="stylesheet" href="../assets/css/custom.css">
   </head>
   <?php if (mysqli_num_rows($result) > 0) {
      // output data of each row
      while($row = mysqli_fetch_assoc($result)) { ?>
   <body id="page-top">
      <div id="wrapper">
         <?php include("navigation.php"); ?>
         <div class="container-fluid">
            <h3 class="text-dark mb-4">Profile</h3>
            <div class="row mb-3">
               <div class="col-lg-4">
                  <div class="card mb-3">
                     <div class="card-body text-center shadow">
                        <img class="rounded-circle mb-3 mt-4" src="<?php echo $image_src; ?>" width="160" height="160">
                        <form method="post" action="profile.php" enctype='multipart/form-data'>
                           <input type="file" id="myFile" name="file">
                           <input type="submit" class="btn btn-primary btn-sm" type="button" value="Change Photo"name="photo_settings">
                        </form>
                     </div>
                  </div>
                  <div class="card shadow mb-4">
                     <div class="card-header py-3">
                        <h6 class="text-primary font-weight-bold m-0">Progress</h6>
                     </div>
                     <div class="card-body">
                        <h4 class="small font-weight-bold">Server migration<span class="float-right">20%</span></h4>
                        <div class="progress progress-sm mb-3">
                           <div class="progress-bar bg-danger" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%;"><span class="sr-only">20%</span></div>
                        </div>
                        <h4 class="small font-weight-bold">Sales tracking<span class="float-right">40%</span></h4>
                        <div class="progress progress-sm mb-3">
                           <div class="progress-bar bg-warning" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;"><span class="sr-only">40%</span></div>
                        </div>
                        <h4 class="small font-weight-bold">Customer Database<span class="float-right">60%</span></h4>
                        <div class="progress progress-sm mb-3">
                           <div class="progress-bar bg-primary" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"><span class="sr-only">60%</span></div>
                        </div>
                        <h4 class="small font-weight-bold">Payout Details<span class="float-right">80%</span></h4>
                        <div class="progress progress-sm mb-3">
                           <div class="progress-bar bg-info" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%;"><span class="sr-only">80%</span></div>
                        </div>
                        <h4 class="small font-weight-bold">Account setup<span class="float-right">Complete!</span></h4>
                        <div class="progress progress-sm mb-3">
                           <div class="progress-bar bg-success" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"><span class="sr-only">100%</span></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-8">
                  <div class="row mb-3 d-none">
                     <div class="col">
                        <div class="card text-white bg-primary shadow">
                           <div class="card-body">
                              <div class="row mb-2">
                                 <div class="col">
                                    <p class="m-0">Peformance</p>
                                    <p class="m-0"><strong>65.2%</strong></p>
                                 </div>
                                 <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                              </div>
                              <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                           </div>
                        </div>
                     </div>
                     <div class="col">
                        <div class="card text-white bg-success shadow">
                           <div class="card-body">
                              <div class="row mb-2">
                                 <div class="col">
                                    <p class="m-0">Peformance</p>
                                    <p class="m-0"><strong>65.2%</strong></p>
                                 </div>
                                 <div class="col-auto"><i class="fas fa-rocket fa-2x"></i></div>
                              </div>
                              <p class="text-white-50 small m-0"><i class="fas fa-arrow-up"></i>&nbsp;5% since last month</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col">
                        <div class="card shadow mb-3">
                           <div class="card-header py-3">
                              <p class="text-primary m-0 font-weight-bold">User Settings</p>
                           </div>
                           <div class="card-body">
                              <form action="profile.php" method="post">
                                 <div class="form-row">
                                    <div class="col">
                                       <div class="form-group"><label for="username"><strong>Username</strong></label><input class="form-control" type="text" placeholder="user.name" name="username" value="<?php echo $row["LoginName"]; ?>" disabled=""></div>
                                    </div>
                                    <div class="col">
                                       <div class="form-group"><label for="email"><strong>Email Address</strong></label><input class="form-control" type="email" placeholder="user@example.com" value="<?php echo $row["Email"]; ?>" name="email"></div>
                                    </div>
                                 </div>
                                 <div class="form-row">
                                    <div class="col">
                                       <div class="form-group"><label for="first_name"><strong>First Name</strong></label><input class="form-control" type="text" placeholder="John" name="first_name" value="<?php echo $row["FirstName"]; ?>"></div>
                                    </div>
                                    <div class="col">
                                       <div class="form-group"><label for="last_name"><strong>Last Name</strong></label><input class="form-control" type="text" placeholder="Doe" name="last_name" value="<?php echo $row["LastName"]; ?>"></div>
                                    </div>
                                 </div>
                                 <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="user_settings">Save Settings</button></div>
                              </form>
                           </div>
                        </div>
                        <div class="card shadow">
                           <div class="card-header py-3">
                              <p class="text-primary m-0 font-weight-bold">Contact Settings</p>
                           </div>
                           <div class="card-body">
                              <form action="profile.php" method="post">
                                 <div class="form-group"><label for="address"><strong>Address</strong></label><input class="form-control" type="text" placeholder="Gotham Nagar, Chennai" value="<?php echo $row["Address"]; ?>" name="address"></div>
                                 <div class="form-row">
                                    <div class="col">
                                       <div class="form-group"><label for="email"><strong>Email</strong></label><input class="form-control" type="email" placeholder="test@test.com" value="<?php echo $row["Email"]; ?>" name="email" disabled=""></div>
                                    </div>
                                    <div class="col">
                                       <div class="form-group"><label for="country"><strong>Phone Number</strong></label><input class="form-control" type="text" placeholder="+91-1234567890" value="<?php echo $row["Phno"]; ?>" name="phno"></div>
                                    </div>
                                 </div>
                                 <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="contact_settings">Save&nbsp;Settings</button></div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="card shadow mb-5">
               <div class="card-header py-3">
                  <p class="text-primary m-0 font-weight-bold">Email Settings</p>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-6">
                        <form action="profile.php" method="post">
                           <div class="form-group"><label for="signature"><strong>Signature</strong><br></label><textarea class="form-control" rows="4" name="signature"><?php echo $row["Signature"]; ?> </textarea></div>
                           <div class="form-group">
                              <div class="custom-control custom-switch"><input class="custom-control-input" type="checkbox" id="formCheck-1"><label class="custom-control-label" for="formCheck-1"><strong>Notify me about new replies</strong></label></div>
                           </div>
                           <div class="form-group"><button class="btn btn-primary btn-sm" type="submit" name="signature_settings">Save Settings</button></div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <footer class="bg-white sticky-footer">
         <div class="container my-auto">
            <div class="text-center my-auto copyright"><span>SVCE ACM Student Chapter</span></div>
         </div>
         <?php    }
            }

            mysqli_close($conn);
            ?>
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
