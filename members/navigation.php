<?php
$displayCircles=array(
                    'normal' => '<div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>',
                    'success' => '<div class="bg-success icon-circle"><i class="fas fa-crosshairs text-white"></i></div>',
                    'warning' => '<div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>'
);

$alertCount=0;
$readReamining=0;
$retAlerts = new mysqli($servername, $username, $password, $dbname);
if ($retAlerts->connect_error) {
	die("Connection failed: " . $retAlerts->connect_error);
}
$retAlertsSQL = 'select notification.timestamp, notification.user, notification.message, notification.type, notification.clickURL, login.imgsrc from notification, login where notification.user=login.LoginName order by notification.id desc limit 3;';
// echo $retAlertsSQL; // For Debugging
// echo "Successfully Logged"; // For Debugging
$alertSQLResults  = $retAlerts->query($retAlertsSQL);
foreach ($alertSQLResults as $rowAlert) {
	$alertArray[$alertCount]["timestamp"]=$rowAlert["timestamp"];
	$alertArray[$alertCount]["user"]=$rowAlert["user"];
	$alertArray[$alertCount]["message"]=$rowAlert["message"];
	$alertArray[$alertCount]["type"]=$rowAlert["type"];
	$alertArray[$alertCount]["clickURL"]=$rowAlert["clickURL"];
	$alertArray[$alertCount]["imgsrc"]=$rowAlert["imgsrc"];
	$alertCount++;
}
$retAlerts->close();

echo '
			<!--  Navigation Panel starts !-->
			<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
				<div class="container-fluid d-flex flex-column p-0">
					<a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
						<div class="sidebar-brand-icon"><img class="logo" src="'.$startPath.'/assets/img/Logo_Banner_White.png"></div>
						<div class="sidebar-brand-text mx-3"></div>
					</a>
					<hr class="sidebar-divider my-0">
					<ul class="nav navbar-nav text-light" id="accordionSidebar">
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/dashboard.php"><i class="fas fa-tachometer-alt"></i><span>&nbsp;Dashboard</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Media &amp; marketing</p>
						</div>
						<li class="nav-item" role="presentation">
						<a class="nav-link" href="'.$startPath.'/members/cds-admin.php"><i class="fas fa-medal"></i><span>&nbsp;Certificate Generator</span></a>
						<a class="nav-link" href="'.$startPath.'/members/mailer.php"><i class="fas fa-mail-bulk"></i><span>&nbsp;Bulk Mailer</span></a>
						<a class="nav-link" href="'.$startPath.'/members/mail-list.php"><i class="fas fa-list"></i><span>&nbsp;Update Mailing list</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Events</p>
						</div>
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/form-gen/"><i class="fab fa-wpforms"></i><span>&nbsp;Form Generator</span></a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/link-short.php"><i class="fas fa-link"></i><span>&nbsp;Link Shortner</span></a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/form-gen/view-reg.php"><i class="fa fa-eye"></i><span>&nbsp;View Registration</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Admin Stuff</p>
						</div>
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/db-manage.php"><i class="fas fa-database"></i><span>&nbsp;Maintainance</span></a></li>
						<hr class="sidebar-divider">
					</ul>

					<div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
				</div>
			</nav>
				<div class="d-flex flex-column" id="content-wrapper">
					<div id="content">
			<nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
			<div class="container-fluid">
				<button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
				<form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
					<div class="input-group">
						<input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
						<div class="input-group-append">
							<button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
						</div>
					</div>
				</form>
				<ul class="nav navbar-nav flex-nowrap ml-auto">
					<li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><i class="fas fa-search"></i></a>
						<div class="dropdown-menu dropdown-menu-right p-3 animated--grow-in" role="menu" aria-labelledby="searchDropdown">
							<form class="form-inline mr-auto navbar-search w-100">
							<div class="input-group">
								<input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
								<div class="input-group-append">
									<button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
								</div>
							</div>
							</form>
						</div>
					</li>
					<li class="nav-item dropdown no-arrow mx-1" role="presentation">
                    <div class="toggle" id="mode_toggler" class="nav-link" >
                        <span class="icon sun"><i class="fas fa-sun"></i></span>
                        <input type="checkbox" id="toggle-switch" onClick="change_mode(sessionStorage.toChange)" />
                        <label for="toggle-switch"><span class="screen-reader-text">Toggle Color Scheme</span></label>
                        <span class="icon moon"><i class="fas fa-moon"></i></span>
                    </div>
					</li>
					<li class="nav-item dropdown no-arrow mx-1" role="presentation">
						<div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="badge badge-danger badge-counter">'.$readReamining.'</span><i class="fas fa-bell fa-fw"></i></a>
							<div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in" role="menu">
							<h6 class="dropdown-header">alerts center</h6><a class="d-flex align-items-center dropdown-item" href="#">';
if(!(empty($alertArray))){
	foreach ($alertArray as $alert){
		if(empty($displayCircles[$alert["type"]])){
			echo '<a class="d-flex align-items-center dropdown-item" href="'.$alert["clickURL"].'"><div class="dropdown-list-image mr-3"><img class="rounded-circle" src="/cms/assets/img/avatars/users/'.$alert["imgsrc"].'" />
				<div class="bg-success"></div>
			</div>
			<div class="font-weight-bold">
				<div class="text-truncate"><span>'.$alert["message"].'</span></div>
				<p class="small text-gray-500 mb-0">'.$alert["user"].' - '.$alert["timestamp"].'</p>
			</div>
			</a>';
		}
		else{
			echo '<a class="d-flex align-items-center dropdown-item" href="">
				<div class="mr-3">
					'.$displayCircles[$alert["type"]].'
				</div>
			<div class="font-weight-bold">
				<div class="text-truncate"><span>'.$alert["message"].'</span></div>
				<p class="small text-gray-500 mb-0">'.$alert["user"].' - '.$alert["timestamp"].'</p>
			</div>
			</a>';
		}
	}
}
else{
	echo '<span class="text-center dropdown-item small text-gray-500">No new alerts</span>';
}
echo '					<a class="text-center dropdown-item small text-gray-500" href="#">Show All Alerts</a></div>
						</div>
					</li>
					<li class="nav-item dropdown no-arrow mx-1" role="presentation">
						<div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
					</li>
					<div class="d-none d-sm-block topbar-divider"></div>
					<li class="nav-item dropdown no-arrow" role="presentation">
						<div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small">'.$loginUser.'</span><img class="border rounded-circle img-profile" src="'.retProfilePic($_SESSION['uname']).'"></a>
							<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu"><a class="dropdown-item" role="presentation" href="'.$startPath.'/members/profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a>
							<a class="dropdown-item" role="presentation" href="'.$startPath.'/members/activity-log.php"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Activity log</a>
							<div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="'.$startPath.'/members/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
						</div>
					</li>
				</ul>
			</div>
			</nav>
		<!--  Navigation panel ends   !-->
		<script src="'.$startPath.'/assets/js/dark-mode.js"></script>';
?>
