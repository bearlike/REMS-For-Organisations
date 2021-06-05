<?php
    session_start();
include("header.php");
    $page = 1;
    $perPage = 10;;
    $totalPages = 1;
    if (empty($_GET['page'])) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }
    if (empty($_GET['perPage'])) {
        $perPage = 10;
    } else {
        $perPage = $_GET['perPage'];
    }
    try{
        $conn = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        $message = $e->getMessage()  ;
        header('pages/error.php?error='.$e->getMessage());
        die();
    }
    $resultc = $conn->prepare('select count(*) from logging where userid=:currentUser');
    $resultc->bindValue(':currentUser', $_SESSION['uname']);
    $resultc->execute();
    $rowc = $resultc->fetch();
    $countr = $rowc[0]; // Count total responses
    // calculate number of pages needed
    $totalPages = ceil($countr / $perPage);
    // Find the starting element for the current $page
    $startPage = $perPage * ($page - 1);
    $sql =  $conn->prepare("select timestamp, log from logging where userid=:currentUser order by id desc limit :startpage , :perpage");
    $sql->bindValue(':currentUser', $_SESSION['uname']);
    $sql->bindValue(':startpage', (int) $startPage, PDO::PARAM_INT);
    $sql->bindValue(':perpage', (int) $perPage, PDO::PARAM_INT);
    $sql->execute();
    $logResults  = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head id="head_tag">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Activity Log:<?php echo " ".$OrgName; ?></title>
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
                <h3 class="text-dark mb-0">Activity Log</h3>
            </div>
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4"></div>
                <div class="col-md-6 col-xl-3 mb-4"></div>
                <div class="col-md-6 col-xl-3 mb-4"></div>
                <div class="col-md-6 col-xl-3 mb-4">
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 font-weight-bold">Activity Log</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-nowrap">
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable">
                                <label>Show&nbsp;
                                    <!-- Form weirdly starts here, don't ask me why :3 !-->
                                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="GET">
                                        <select onchange="this.form.submit()" name="perPage" class="form-control form-control-sm custom-select custom-select-sm">
                                            <option value="10" <?php if ($perPage == 10) {
                                                                    echo 'selected=""';
                                                                } ?>>10</option>
                                            <option value="25" <?php if ($perPage == 25) {
                                                                    echo 'selected=""';
                                                                } ?>>25</option>
                                            <option value="50" <?php if ($perPage == 50) {
                                                                    echo 'selected=""';
                                                                } ?>>50</option>
                                            <option value="100" <?php if ($perPage == 100) {
                                                                    echo 'selected=""';
                                                                } ?>>100</option>
                                        </select>&nbsp;
                                </label></div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-right dataTables_filter" id="dataTable_filter"><label></label>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
                        <table class="table dataTable my-0 table-md table-hover" id="dataTable">
                            <thead>
                                <tr>
                                    <th class="table-info">Timestamp</th>
                                    <th class="table-info">Log</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($logResults as $row) {
                                    echo "<tr>";
                                    echo "<td>" . $row["timestamp"] . "</td>";
                                    echo "<td>" . $row["log"] . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="table-info">Timestamp</th>
                                    <th class="table-info">Log</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 align-self-center">
                            <p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite"></p>
                        </div>
                        <form class="col-md-6">
                            <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                <ul class="pagination">
                                    <li class="page-item <?php if ($page == 1) {
                                                                echo 'disabled';
                                                            } ?>"><button name="page" value="<?php echo ($page - 1); ?>" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span></button>
                                    </li>
                                    <input type="hidden" name="event" value="<?php echo $_GET['event']; ?>" />
                                    <input type="hidden" name="perPage" value="<?php echo $perPage; ?>" />
                                    <?php
                                    // Generate buttons for choosing pages
                                    for ($x = 1; $x <= $totalPages; $x++) {
                                        if ($x == $page) {
                                            echo '<li class="page-item active"><button name="page" value="' . $x . '" class="page-link">' . $x . '</button></li>';
                                        } else {
                                            echo '<li class="page-item"><button name="page" value="' . $x . '" class="page-link">' . $x . '</button></li>';
                                        }
                                    }
                                    ?>

                                    <li class="page-item <?php if ($page == $totalPages) {
                                                                echo 'disabled';
                                                            } ?>"><button name="page" value="<?php echo ($page + 1); ?>" class="page-link" aria-label="Next"><span aria-hidden="true">»</span></button></li>
                                </ul>
                            </nav>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <br><br>
    </div>
    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
            <div class="text-center">Made with ❤️ by <a href="https://thekrishna.in/">Krishnakanth</a> and <a href="https://www.linkedin.com/in/mahavisvanathan/">Mahalakshumi</a></div>
        </div>
    </footer>
    </div>
    <a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/theme.js"></script>
</body>

</html>