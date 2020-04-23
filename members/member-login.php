<?php
    session_start();
    if (!empty($_SESSION)){
        if ($_SESSION['remember']==1){
            if ($_SESSION['uname']){
                header('Location: validate.php');
            }
        }
        else
            session_destroy();
    }
    if((isset($_COOKIE["username"])) && (isset($_COOKIE["password"]))) {
        header('Location: validate.php');
    }
?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login: SVCE-ACM</title>
    <link rel="icon" type="image/png" sizes="600x600" href="../assets/img/Logo_White.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
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
                                <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;../assets/img/front/image2.png&quot;);"></div>
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h6 class="text-dark mb-2">SVCE-ACM Student Chapter</h6>
                                        <h4 class="text-dark mb-4">Members Portal</h4>
                                    </div>
                                    <form class="user" action="validate.php" method="POST">
                                        <div class="form-group"><input type="hidden" value="unknown" name="ipadd" id="ipadd" /><input class="form-control form-control-user" type="text" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Username..." name="uname" required></div>
                                        <div class="form-group"><input class="form-control form-control-user" type="password" id="exampleInputPassword" placeholder="Password (Shhh....)" name="password" required></div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <div class="form-check"><input class="form-check-input custom-control-input" name="remember" value="1" type="checkbox" id="formCheck-1"><label class="form-check-label custom-control-label" for="formCheck-1">Remember Me</label></div>
                                            </div>
                                        </div><button class="btn btn-primary btn-block text-white btn-user" type="submit">Login</button>
                                        <hr>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.php">Forgot Password?</a></div>
                                    <div class="text-center"><a class="small" href="../index.php">Looking for our Certificate Distribution System (CDS)?</a></div>
                                    <div class="text-center"></div>
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
    <script>
        /* To retrieve client IP Address */
        function populatePre(url) {
            const regex = /ip=[0-9]+.[0-9]+.[0-9]+.[0-9]+/gm;
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                while ((m = regex.exec(this.responseText)) !== null) {
                    // This is necessary to avoid infinite loops with zero-width matches
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }
                    // The result can be accessed through the `m`-variable.
                    m.forEach((match, groupIndex) => {
                        var ip = match.replace("ip=", "");
                        document.getElementById('ipadd').value = (ip);
                    });
                }
            };
            xhr.open('GET', url);
            xhr.send();
        }
        populatePre("https://www.cloudflare.com/cdn-cgi/trace");
    </script>

</body>

</html>
