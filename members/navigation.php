<?php
echo '		<!--  Navigation Panel starts !-->
			<nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0">
				<div class="container-fluid d-flex flex-column p-0">
					<a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
						<div class="sidebar-brand-icon"><img class="logo" src="/cms/assets/img/Logo_Banner_White.png"></div>
						<div class="sidebar-brand-text mx-3"></div>
					</a>
					<hr class="sidebar-divider my-0">
					<ul class="nav navbar-nav text-light" id="accordionSidebar">
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/dashboard.php"><i class="fas fa-tachometer-alt"></i><span>&nbsp;Dashboard</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Media &amp; marketing</p>
						</div>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/cds-admin.php"><i class="fas fa-medal"></i><span>&nbsp;Certificate Generator</span></a><a class="nav-link" href="/cms/members/mailer.php"><i class="fas fa-mail-bulk"></i><span>&nbsp;Bulk Mailer</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Events</p>
						</div>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/form-gen/"><i class="fab fa-wpforms"></i><span>&nbsp;Form Generator</span></a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/link-short.php"><i class="fas fa-link"></i><span>&nbsp;Link Shortner</span></a></li>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/form-gen/view-reg.php"><i class="fa fa-eye"></i><span>&nbsp;View Registration</span></a></li>
						<hr class="sidebar-divider">
						<div class="sidebar-heading">
							<p class="mb-0">Admin Stuff</p>
						</div>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cms/members/db-manage.php"><i class="fas fa-database"></i><span>&nbsp;Maintainance</span></a></li>
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
						<div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="badge badge-danger badge-counter">3+</span><i class="fas fa-bell fa-fw"></i></a>
							<div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in" role="menu">
							<h6 class="dropdown-header">alerts center</h6>
							<a class="d-flex align-items-center dropdown-item" href="#">
								<div class="mr-3">
									<div class="bg-primary icon-circle"><i class="fas fa-file-alt text-white"></i></div>
								</div>
								<div><span class="small text-gray-500">December 12, 2019</span>
									<p>Sample Text</p>
								</div>
							</a>
							<a class="d-flex align-items-center dropdown-item" href="#">
								<div class="mr-3">
									<div class="bg-success icon-circle"><i class="fas fa-donate text-white"></i></div>
								</div>
								<div><span class="small text-gray-500">December 7, 2019</span>
									<p>Sample Text</p>
								</div>
							</a>
							<a class="d-flex align-items-center dropdown-item" href="#">
								<div class="mr-3">
									<div class="bg-warning icon-circle"><i class="fas fa-exclamation-triangle text-white"></i></div>
								</div>
								<div><span class="small text-gray-500">December 2, 2019</span>
									<p>Sample Text</p>
								</div>
							</a><a class="text-center dropdown-item small text-gray-500" href="#">Show All Alerts</a></div>
						</div>
					</li>
					<li class="nav-item dropdown no-arrow mx-1" role="presentation">
						<div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
					</li>
					<div class="d-none d-sm-block topbar-divider"></div>
					<li class="nav-item dropdown no-arrow" role="presentation">
						<div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small">'.$loginUser.'</span><img class="border rounded-circle img-profile" src="/cms/assets/img/avatars/avatar1.jpeg"></a>
							<div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu"><a class="dropdown-item" role="presentation" href="/cms/members/profile.php"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" role="presentation" href="/cms/members/settings.php"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Settings</a>
							<a class="dropdown-item" role="presentation" href="/cms/members/activity-log.php"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Activity log</a>
							<div class="dropdown-divider"></div><a class="dropdown-item" role="presentation" href="/cms/members/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
						</div>
					</li>
				</ul>
			</div>
			</nav>
		<!--  Navigation panel ends   !-->
';
?>