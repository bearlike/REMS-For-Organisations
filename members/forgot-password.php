<?php
include("secrets_.php");
include("mailer-templates/forgot_pwd_temp.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../src/PHPMailer/Exception.php';
require '../src/PHPMailer/PHPMailer.php';
require '../src/PHPMailer/SMTP.php';


$conn = new mysqli($servername, $username, $password, $MainDB);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
                                        <h4 class="text-dark mb-2">Forgot Your Password?</h4>
                                        <p class="mb-4">We get it, stuff happens. Just enter your email address below and we'll send you a link to reset your password!</p>
                                    </div>
                                    <form class="user" action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
                                        <div class="form-group"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="email"></div><button class="btn btn-primary btn-block text-white btn-user"
                                            type="submit" name="submit">Reset Password</button></form>
                                    <div class="text-center">
                                        <hr>
                                    </div>
                                    <div class="text-center"><a class="small" href="member-login.php">Already have an account? Login!</a></div>
                                    <?php
                                        if (isset($_POST["submit"])){
                                            $email = $_POST['email'];
                                            $check_sql = 'SELECT count(1) as isPresent from login where Email="'.$email.'"';
                                            $list_check = $conn->query($check_sql);
                                            foreach ($list_check as $entry) {
                                                $isUser =  $entry['isPresent'];
                                            }
                                            if($isUser){
                                                $hash_sql = 'SELECT ForgotPasswordHash("'.$email.'") as link_value';
                                                $list  = $conn->query($hash_sql);
                                                foreach ($list as $row) {
                                                    $key =  $row['link_value'];
                                                }
                                                $mail = new PHPMailer();  // create a new object
                                                $mail->IsSMTP();
                                                $mail->isHTML(true);
                                                $mail->SMTPDebug = 0;
                                                $mail->SMTPAuth = true;
                                                $mail->SMTPSecure = 'ssl';
                                                $mail->Host = $mailerHostname;
                                                $mail->Port = 465;
                                                $mail->Username = $mailerUname;
                                                $mail->Password = $mailerPassword;
                                                $mail->SetFrom($mailerUname, "Technical support");
                                                $mail->AddBCC($email);
                                                $mail->Subject = "Request For Password - Reg";
                                                $link = $hostName.$forgotPwdExtension.'?gen='.$key;
                                                $mail->Body = forgot_password_mail($darkLogo,$logoHREF,$link,$reachEmail);
                                                if(!$mail->Send()) {
                                                	echo 'Mail error: '.$mail->ErrorInfo;
                                                } else {
                                                    echo('<div class="alert alert-success" role="alert" style="width:70%;margin-left:15%;margin-right:15%">
                                                    Your e-mail has been sent!
                                                    </div>');
                                                }
                                            }else{
                                                echo('<div class="alert alert-danger" role="alert" style="width:70%;margin-left:15%;margin-right:15%">
                                                This account does not exist!
                                                </div>');
                                            }

                                        }
                                     ?>
                                </div>
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
