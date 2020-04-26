<?php
$displayCircles=array(
                    'normal' => '<div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>',
				'success' => '<div class="bg-success icon-circle"><i class="fas fa-crosshairs text-white"></i></div>',
				'info' => '<div class="bg-success icon-circle"><i class="fas fa-info-circle text-white"></i></div>',
                    'warning' => '<div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>'
);

$alertCount=0;
$readReamining="";
$navConn = new PDO('mysql:dbname='.$MainDB.';host='.$servername.';charset=utf8', $username, $password);
$navConn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$navConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$retAlertsSQL = $navConn->prepare('select notification.timestamp, notification.user, notification.message, notification.type, notification.clickURL, login.imgsrc from notification, login where notification.user=login.LoginName order by notification.id desc limit 3;');
$retAlertsSQL->execute();
// echo $retAlertsSQL; // For Debugging
// echo "Successfully Logged"; // For Debugging
$alertSQLResults  = $retAlertsSQL->fetchAll(PDO::FETCH_ASSOC);
foreach ($alertSQLResults as $rowAlert) {
	$alertArray[$alertCount]["timestamp"]=$rowAlert["timestamp"];
	$alertArray[$alertCount]["user"]=$rowAlert["user"];
	$alertArray[$alertCount]["message"]=$rowAlert["message"];
	$alertArray[$alertCount]["type"]=$rowAlert["type"];
	$alertArray[$alertCount]["clickURL"]=$rowAlert["clickURL"];
	$alertArray[$alertCount]["imgsrc"]=$rowAlert["imgsrc"];
	$alertCount++;
}
$navConn=null;
$retAlertsSQL=null;

echo '
			<!--  Navigation Panel starts !-->
			<nav id="navbar" class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
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
						<a class="nav-link" href="'.$startPath.'/members/mail-list.php"><i class="fas fa-list"></i><span>&nbsp;Update Mailing List</span></a></li>
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
						<li class="nav-item" role="presentation"><a class="nav-link" href="'.$startPath.'/members/db-manage.php"><i class="fas fa-database"></i><span>&nbsp;Maintenance</span></a></li>
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
			echo '<a class="d-flex align-items-center dropdown-item" href="'.$alert["clickURL"].'"><div class="dropdown-list-image mr-3"><img class="rounded-circle" src="'.$startPath.'/assets/img/avatars/users/'.$alert["imgsrc"].'" />
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
		<script src="'.$startPath.'/assets/js/dark-mode.js"></script>
		<script>
			var isMobile = false; //initiate as false
			// device detection
			if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
				|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) { 
				isMobile = true;
				document.getElementById(\'navbar\').classList.add(\'toggled\');
			}
		</script>
';
		
?>
