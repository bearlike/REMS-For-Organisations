<?php include("header.php"); ?>
<html>

<head>
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
                <form>
                    <h5 style="margin-top: 0;padding-top: 15px;">Choose the mailing list(s)</h5>
                    <div class="form-row" id="choose-list" style="margin-left: 10px;margin-top: 0px;margin-right: 10px;padding-top: 0px;">
                        <div class="col">
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">CSE Only (1-4 Years)</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">All Departments in SVCE</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Inter-College Mailing List</label></div>
                        </div>
                        <div class="col-xl-5">
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">CSE Only (1-4 Years)</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">All Departments in SVCE</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Inter-College Mailing List</label></div>
                        </div>
                        <div class="col">
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">CSE Only (1-4 Years)</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">All Departments in SVCE</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" id="formCheck-1"><label class="form-check-label" for="formCheck-1">Inter-College Mailing List</label></div>
                        </div>
                    </div>
                    <div class="form-row" id="subject" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><input class="form-control form-control" type="text" placeholder="Subject" required=""></div>
                        </div>
                    </div>
                    <div class="form-row" id="body" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><textarea class="form-control form-control" placeholder="Mail Body" required="" style="height: 350px;min-height: 250px;"></textarea></div>
                        </div>
                    </div>
                    <div class="form-row" id="submit-btn" style="margin-left: 10px;margin-top: 14px;margin-right: 10px;">
                        <div class="col">
                            <div class="form-group"><button class="btn btn-primary" type="button">&nbsp;<i class="fa fa-send"></i>&nbsp; &nbsp; &nbsp;Send Message&nbsp; &nbsp;</button></div>
                        </div>
                    </div>
                </form>
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