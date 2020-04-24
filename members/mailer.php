<?php
include("header.php");
include("mailer-templates/make_mail.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../src/PHPMailer/Exception.php';
require '../src/PHPMailer/PHPMailer.php';
require '../src/PHPMailer/SMTP.php';
$conn = new mysqli($servername, $username, $password, $mailerDB);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "show TABLES from ".$mailerDB;
$tableNames = $conn->query($sql);
?>

<html>

<head id="head_tag">
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
     <title>Bulk Mailer:<?php echo " ".$OrgName; ?></title>
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
               <h3 class="text-dark mb-1">Bulk Mailer</h3>
               <form action="<?php echo $_SERVER["PHP_SELF"]?>" method="post">
                    <h5 style="margin-top: 0;padding-top: 15px;">Choose the mailing list(s)</h5>
                    <div class="form-row" id="choose-list"
                         style="margin-left: 10px;margin-top: 0px;margin-right: 10px;padding-top: 0px;">
                         <div class="col">
                              <select name="mailing_list" class="form-control border-1 small" style="width:40%">
                                   <?php
                            foreach ($tableNames as $row) {
                                echo '<option value="'.$row["Tables_in_".$mailerDB].'">'.ucwords(str_replace("_"," ",$row["Tables_in_".$mailerDB])).'</option>';
                            }
                            ?>
                              </select>
                         </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Subject" required="" name="mail_subject"></div>
                         </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Title of the E-mail" required="" name="mail_title" id="mail_title">
                              </div>
                         </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Button label" required="" name="mail_button_label"
                                        id="mail_button_label" value="<?php echo $buttonLabel; ?>"></div>
                         </div>
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Button URL" required="" name="mail_button_url" id="mail_button_url"
                                        value="<?php echo $buttonURL; ?>"></div>
                         </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Logo URL" required="" name="mail_logo_url" id="mail_logo_url"
                                        value="<?php echo $logoURL; ?>"></div>
                         </div>
                         <div class="col">
                              <div class="form-group"><input class="form-control form-control" type="text"
                                        placeholder="Cover Image URL" required="" name="mail_coverimg_url"
                                        id="mail_coverimg_url" value="<?php echo $coverURL; ?>"></div>
                         </div>
                    </div>
                    <div class="form-row" id="body" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col">
                              <div class="form-group"><textarea class="form-control form-control"
                                        placeholder="Mail Body" required="" style="height: 350px;min-height: 250px;"
                                        name="mail_body" id="mail_body"></textarea></div>
                         </div>
                    </div>
                    <div class="form-row" id="submit-btn"
                         style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                         <div class="col center">
                              <div class="form-group"><button class="btn btn-primary" type="submit"
                                        name="submit">&nbsp;<i class="fa fa-send"></i>&nbsp; &nbsp; &nbsp;Send
                                        Message&nbsp; &nbsp;</button></div>
                         </div>
                         <div class="col center">
                              <div class="form-group"><button class="btn btn-primary" type="button"
                                        onClick="preview()">&nbsp;<i class="fas fa-eye"></i>&nbsp; &nbsp; &nbsp;Preview
                                        message&nbsp; &nbsp;</button></div>
                         </div>
                    </div>
               </form>
          </div>
          <?php
            if (isset($_POST["submit"])){

                $mailing_list = $_POST["mailing_list"];

                $mail_subject = $_POST["mail_subject"];

                $mail_title = $_POST["mail_title"];
                $mail_button_label = $_POST["mail_button_label"];
                $mail_button_url = $_POST["mail_button_url"];
                $mail_logo_url = $_POST["mail_logo_url"];
                $mail_coverimg_url = $_POST["mail_coverimg_url"];
                $mail_body = $_POST["mail_body"];

                $mail_generated=make_mail($mail_body,$mail_title,$mail_button_url,$mail_button_label,$mail_logo_url,$mail_coverimg_url);
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
                $mail->Body = $mail_generated;
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
                logActivity($_SESSION['uname'], 'Mail(s) sent to mailer[' . $mailing_list . '], Sub: [' . $mail_subject . ']');
            }
             ?>

          <script src="mailer-templates/preview_mail.js"></script>
          <script>
          function preview() {
               var myWindow = window.open("", "_blank");
               var body = document.getElementById("mail_body").value;
               body = body.replace(/\r?\n|\r/g, "<br>");
               var title = document.getElementById("mail_title").value;
               var button_link = document.getElementById("mail_button_url").value;
               var button_label = document.getElementById("mail_button_label").value;
               var logourl = document.getElementById("mail_logo_url").value;
               var coverimgurl = document.getElementById("mail_coverimg_url").value;
               var email = preview_email(body, title, button_link, button_label, logourl, coverimgurl);
               myWindow.document.write(email);
          }
          </script>
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
