<?php
    include("header.php");
    $conn1 = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);
    $conn1->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //$conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn2 = new PDO('mysql:dbname='.$formDB.';host='.$servername.';charset=utf8', $username, $password);
    $conn2->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //$conn2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $resultc = $conn1->prepare('SELECT CONCAT("event_",LOWER(REPLACE((SELECT `event_name` FROM `events` order by `date` desc limit 1)," ","_"))) as code');
    $resultc->execute();
    $rowc = $resultc->fetch();
    $eventTable = $rowc[0]; // Return Latest Event Name
    //echo $eventTable;
    $ifTableExistSQL = $conn2->prepare('SELECT count(1) FROM information_schema.TABLES WHERE (TABLE_SCHEMA = :formDB) AND (TABLE_NAME = :eventTable)');
    $ifTableExistSQL->bindValue(':formDB', $formDB);
    $ifTableExistSQL->bindValue(':eventTable', $eventTable);
    $ifTableExistSQL->execute();
    $ifTableExist = $ifTableExistSQL->fetch();

    if($ifTableExist[0]==1){
        $evCountStmt = $conn2->prepare('SELECT count(*) from '.$eventTable);
        $evCountStmt->execute();
        $resultc = $evCountStmt->fetch();
        $registrationCount = $resultc[0]; // Count total responses
    }
    else{
        $registrationCount = "Form not Generated";
    }
    $conn2=null;
    $resultc = $conn1->prepare('SELECT COUNT(*) as code FROM events;');
    $resultc->execute();
    $rowc = $resultc->fetch();
    $eventsCount = $rowc[0]; // Count total responses

?>
<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard:<?php echo " ".$OrgName; ?></title>
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
                    <h3 class="text-dark mb-0">Welcome!</h3><a class="btn btn-danger btn-sm d-none d-sm-inline-block" role="button" href="https://github.com/K-Kraken/REMS-For-Organisations/issues" style="background-color: #ce1126;border-color: #e5053a;"><i class="fas fa-bug fa-sm text-white-50"></i>&nbsp;Raise a Issue</a></div>
                <div
                    class="row">
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow border-left-primary py-2">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col mr-2">
                                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Registrations for <?php echo ucwords(str_replace("event ","",(str_replace("_"," ",$eventTable)))); ?> (Latest)</span></div>
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
                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="text-primary m-0 font-weight-bold">Send Alerts&nbsp;<i class="fa fa-send"></i></h6>
                                </div>
                                <div class="card-body">
                                    <p class="m-0" style="padding-bottom: 10px;">You can send alerts to all from here through the alert center. You username would be attached to your message.<br /></p>
                                    <form method="POST" action="db-ops/pushAlert.php">
                                        <?php if(retIsAdmin($_SESSION['uname'])==1){echo '<div class="form-group">
                                            <div class="dropdown">
                                                <select class="btn btn-sm btn-primary dropdown-toggle" name="type">
                                                    <option value="none" class="" selected>None</option>
                                                    <option value="normal" class="">Normal</option>
                                                    <option value="info" class="">Infomation</option>
                                                    <option value="success" class="">Success</option>
                                                    <option value="warning" class="">Warning</option>
                                                </select>
                                            </div>
                                        </div>';}
                                        else{
                                            echo '<input type="hidden" value="none" name="type" />';
                                        }
                                        ?>
                                        <div class="form-group"><input type="text" class="form-control btn-sm" placeholder="Enter the message to be sent" name="alertMessage" /></div>
                                        <div class="form-group"><input type="submit" class="form-control btn btn-primary btn-sm" /></div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div id="newCol" class=""></div>
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
    <script>
        if(!(isMobile)){
            document.getElementById('newCol').classList.add('col');
        }
    </script>
</body>

</html>
