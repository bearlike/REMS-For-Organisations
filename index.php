<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Certificate Distribution System (CDS): SVCE-ACM</title>
    <link rel="icon" type="image/png" sizes="600x600" href="assets/img/Logo_White.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex">
                                <div class="flex-grow-1 bg-password-image" style="background-image: url(&quot;assets/img/dogs/image2.jpeg&quot;);"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-2">Certificate Distribution System (CDS)</h4>
                                        <p class="mb-4">We're here to make your life much easier. Just enter the event name and watch then watch happen!</p>
                                    </div>
                                    <form class="user" action="public/cds-public.php" method="GET">
                                        <div class="form-group"><input class="form-control form-control-user" type="text" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Event Name..." name="event" required=""></div><button class="btn btn-primary btn-block text-white btn-user"
                                            type="submit">Search Event</button></form>
                                            <div class="text-center"><p class="mb-4 small" style="color: red;" >
                                            <?php 
                                                if (!empty($_GET)) {
                                                    echo "Event not found. Are you sure you're spelling it right?";
                                                } 
                                            ?>
                                            </p></div>
                                    <div class="text-center">
                                        <hr>
                                    </div>
                                    <div class="text-center"><a class="small" href="public/cds-public.php?mode">Don't Know the Event Name? Click here.</a></div>
                                    <div class="text-center"><a class="small" href="members/member-login.php">Are you a member? Login!</a></div>
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
    <script src="assets/js/theme.js"></script>
</body>

</html>