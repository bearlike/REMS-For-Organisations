<?php
    include("header.php");
    $conn1 = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn1->connect_error) {
        die("Connection failed: " . $conn1->connect_error);
    }
    $conn2 = new mysqli($servername, $username, $password, $formDB);
    // Check connection
    if ($conn2->connect_error) {
        die("Connection failed: " . $conn1->connect_error);
    }
    $resultc = $conn1->query('SELECT CONCAT("event_",LOWER(REPLACE((SELECT `event_name` FROM `events` order by `date` desc limit 1)," ","_"))) as code');
    $rowc = $resultc->fetch_row();
    $eventsCount = $rowc[0]; // Count total responses
    $resultc = $conn2->query('SELECT COUNT(*) as code FROM '.$eventsCount);
    if($resultc){
        $rowc = $resultc->fetch_row();
        $registrationCount = $rowc[0]; // Count total responses
    }
    else{
        $registrationCount = 0;
    }
    $resultc = $conn1->query('SELECT COUNT(*) as code FROM events;');
    $rowc = $resultc->fetch_row();
    $eventsCount = $rowc[0]; // Count total responses

?>
<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard: SVCE-ACM CMS</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="../assets/css/custom.css">
</head>

<body id="page-top">
    <div id="wrapper">
     <?php include("navigation.php"); ?>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-0">Welcome!</h3><a class="btn btn-danger btn-sm d-none d-sm-inline-block" role="button" href="https://github.com/K-Kraken/cms-for-organisations/issues" style="background-color: #ce1126;border-color: #e5053a;"><i class="fas fa-bug fa-sm text-white-50"></i>&nbsp;Raise a Issue</a></div>
                <div
                    class="row">
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-left-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Registrations (Latest)</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $registrationCount; ?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-friends fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-left-success py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>Total Members</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span>37</span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-user-ninja fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-left-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Events conducted so far</span></div>
                                        <div class="text-dark font-weight-bold h5 mb-0"><span><?php echo $eventsCount; ?></span></div>
                                    </div>
                                    <div class="col-auto"><i class="fas fa-calendar-day fa-2x text-gray-300"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="text-primary m-0 font-weight-bold">Let's get started</h6>
                        </div>
                        <div class="card-body">
                            <p class="m-0">This is a software application to reduce amount of menial work we do. We know it is a tedious process to manually make forms, certificates, advertising via mail so we decided to automate that process. I guess it's all pretty
                                self explainatory. Knock yourself out.&nbsp;<br></p>
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
    </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>
