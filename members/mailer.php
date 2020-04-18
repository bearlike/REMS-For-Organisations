<?php include("header.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '..\src\PHPMailer\Exception.php';
require '..\src\PHPMailer\PHPMailer.php';
require '..\src\PHPMailer\SMTP.php';
$conn = new mysqli($servername, $username, $password, $mailerDB);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Bulk Mailer: SVCE-ACM CMS</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
    <?php include("navigation.php"); ?>
    <div class="container-fluid">
                <h3 class="text-dark mb-1">Bulk Mailer</h3>
                <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
                    <h5 style="margin-top: 0;padding-top: 15px;">Choose the mailing list(s)</h5>
                    <div class="form-row" id="choose-list" style="margin-left: 10px;margin-top: 0px;margin-right: 10px;padding-top: 0px;">
                        <div class="col">
                            <div class="form-check"><input class="form-check-input" type="radio" name="mailing_list" value="test_list"><label class="form-check-label">Test mailing list</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="mailing_list" value="test_list"><label class="form-check-label">Test mailing list</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="mailing_list" value="test_list"><label class="form-check-label">Test mailing list</label></div>
                        </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><input class="form-control form-control" type="text" placeholder="Subject" required="" name="mail_subject"></div>
                        </div>
                    </div>
                    <div class="form-row" id="body" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><textarea class="form-control form-control" placeholder="Mail Body" required="" style="height: 350px;min-height: 250px;" name="mail_body"></textarea></div>
                        </div>
                    </div>
                    <div class="form-row" id="submit-btn" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><button class="btn btn-primary" type="submit" name="submit">&nbsp;<i class="fa fa-send"></i>&nbsp; &nbsp; &nbsp;Send Message&nbsp; &nbsp;</button></div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            if (isset($_POST["submit"])){

                $mailing_list = $_POST["mailing_list"];
                $mail_subject = $_POST["mail_subject"];
                $mail_body = $_POST["mail_body"];

                $get_columns_sql = "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='".$mailerDB."' AND `TABLE_NAME`='".$mailing_list."'";
                $columns = $conn->query($get_columns_sql); // COLUMN_NAME
                $i=0;
                foreach ($columns as $row) {
                    $colArr[$i]=$row['COLUMN_NAME'];
                    $i++;
                }

                $sql = "select * from ".$mailing_list." ;";
                $list  = $conn->query($sql);

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
                $mail->SetFrom($mailerUname, "Organizing team");
                $mail->Subject = $mail_subject;
                $mail->Body = $mail_body;
                foreach ($list as $row){
                        $mail->AddBCC($row['email']);
                }
                	if(!$mail->Send()) {
                		echo 'Mail error: '.$mail->ErrorInfo;
                	} else {
                        echo('<div class="alert alert-success" role="alert">
                                  Your e-mail has been sent!
                                </div>');
                	}
            }
             ?>
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
