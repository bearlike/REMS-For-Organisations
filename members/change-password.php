<?php
include('mainFunction.php');
include("secrets_.php");

try{
    $conn = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    $message = $e->getMessage()  ;
    header('Location:../public/error.html');
    die();
}
$gen = null;
if(isset($_GET['gen'])){
    $gen = $_GET['gen'];
    $check_if_done = $conn->prepare('SELECT count(PasswordLinkVerification(:gen)) as ifExists');
    $check_if_done->bindValue(":gen",$gen);
    $check_if_done->execute();
    foreach ($check_if_done as $row) {
        $exists = $row['ifExists'];
    }
    if($exists==1){
        $check_validity_query = $conn->prepare('SELECT PasswordLinkVerification(:gen) as time_diff');
        $check_validity_query->bindValue(":gen",$gen);
        $check_validity_query->execute();
        foreach ($check_validity_query as $row_time) {
            $key =  $row_time['time_diff'];
        }
        if($key<1800){
        }
        else{
            $check_validity_query = 'DELETE FROM forgot_password WHERE gen_key=:gen';
            $check_validity_query->bindValue(":gen",$gen);
            $check_validity_query->execute();
            header('Location:link-expired.html');
        }
    }else{
        header('Location:link-expired.html');
    }


}else{
    header('Location:member-login.php');
}

?>

<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgotten Password -<?php echo " ".$OrgName; ?></title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex">
                                <div class="flex-grow-1 bg-password-image" style="background-image: url(&quot;../assets/img/front/image2.png&quot;);"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-2">Create your new password</h4>
                                    </div>
                                    <form class="user" action="<?php echo $_SERVER["PHP_SELF"]?>" method="post" onSubmit="return checkMatch();">
                                        <input type="hidden" name="gen" value =<?php echo $gen;?>>
                                        <div class="form-group"><input class="form-control form-control-user" type="password" id="pwd" placeholder="Enter New Password" name="pwd"></div>
                                        <div class="form-group"><input class="form-control form-control-user" type="password" id="pwd_confirm"  placeholder="Confirm password" name="pwd_confirm"></div>
                                        <p style='text-align:center;font-size:15px;color:red' id="password_match"></p>
                                        <button class="btn btn-primary btn-block text-white btn-user"
                                            type="submit" name="submit">Reset Password</button></form>
                                    <div class="text-center">
                                        <hr>
                                    </div>
                                    <div class="text-center"><a class="small" href="member-login.php">Already have an account? Login!</a></div>
                                    <?php

                                        if (isset($_POST["submit"])){
                                            $gen = $_POST["gen"];
                                            $get_uname_sql = $conn->prepare('SELECT GetUserName(:gen) as uname');
                                            $get_uname_sql->bindValue(":gen",$gen);
                                            $get_uname_sql->execute();
                                            foreach ($get_uname_sql as $user_name) {
                                                $uname  =  $user_name['uname'];
                                            }
                                            $password = $_POST['pwd_confirm'];
                                            $update_sql = $conn->prepare('CALL SetPassword(:gen,:password)');
                                            $update_sql->bindValue(":gen",$gen);
                                            $update_sql->bindValue(":password",$password);
                                            $update_sql->execute();
                                            echo('<div class="alert alert-success" role="alert" style="width:80%;margin-left:10%;margin-right:10%">
                                            Password updated successfuly!
                                            </div>');
                                                logActivity($uname,"Password changed");
                                            }
                                     ?>
                                </div>
                                <script>
                                    function checkMatch(){
                                        var pwd1 = document.getElementById('pwd').value;
                                        var pwd2 = document.getElementById('pwd_confirm').value;
                                        if(pwd1.replace(" ","").length==0 || pwd2.replace(" ","").length==0){
                                            document.getElementById('password_match').innerHTML = 'Fields Empty';
                                            return false;
                                        }
                                        if(pwd1==pwd2){
                                            return true;
                                        }else{
                                            document.getElementById('password_match').innerHTML = 'Passwords do not match!';
                                            return false;
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>
