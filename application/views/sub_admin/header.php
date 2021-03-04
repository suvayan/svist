<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="<?php echo base_url().'assets/img/favicon.png'; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
	<?php echo $pageTitle; ?>
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--  Social tags      -->
  <meta name="keywords" content="">
  <meta name="description" content="">

  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="<?php echo base_url(); ?>assets/css/material-dashboard.min1c51.css?v=2.1.2" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/demo/demo.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/vendor/owlcarousel/css/owl.carousel.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/vendor/jquery-ui/jquery-ui.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
  <!--   Core JS Files   -->
  <script src="<?php echo base_url(); ?>assets/js/core/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-notify.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/jquery-ui.js"></script>  
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
  <script src="<?php echo base_url().'assets/vendor/ckeditor/ckeditor.js'; ?>" type="text/javascript"></script>
  <script>var baseURL='<?php echo base_url(); ?>'; var uType = "<?php echo $_SESSION['userData']['utype']; ?>";</script>
</head>

<body>
<div class="preloader" id="loader">
	<img src="<?php echo base_url().'assets/img/Preloader_3.gif'; ?>">
</div>
<div class="wrapper">
	<div class="sidebar" data-color="purple" data-background-color="black" data-image="<?php echo base_url(); ?>assets/img/sidebar-1.jpg">
		<div class="sidebar-wrapper">
			<div class="user">
				<div class="photo">
					<img src="<?php echo base_url().$_SESSION['userData']['photo']; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/default-avatar.png'" />
				</div>
				<div class="user-info">
					<a data-toggle="collapse" href="#sideProfile" class="username">
					  <span>
						<?php echo $_SESSION['userData']['name']; ?>
						<b class="caret"></b>
					  </span>
					</a>
					<div class="collapse" id="sideProfile">
						<ul class="nav">
							<li class="nav-item">
							  <a class="nav-link" href="<?php echo base_url().'Teacher/userProfile'; ?>">
								<span class="sidebar-mini"> MP </span>
								<span class="sidebar-normal"> My Profile </span>
							  </a>
							</li>
							<li class="nav-item">
							  <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#passModal">
								<span class="sidebar-mini"> S </span>
								<span class="sidebar-normal"> Change Password </span>
							  </a>
							</li>
									<li class="nav-item">
							  <a class="nav-link" href="<?php echo base_url('Teacher/logout'); ?>">
								<span class="sidebar-mini"> L </span>
								<span class="sidebar-normal"> Logout </span>
							  </a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<ul class="nav">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url('Subadmin'); ?>">
					  <i class="material-icons">dashboard</i>
					  <p> Dashboard </p>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url('Subadmin/programMaster'); ?>">
					  <i class="material-icons">article</i>
					  <p> Programs </p>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url('Subadmin/teacherMaster'); ?>">
					  <i class="material-icons">school</i>
					  <p> Teacher </p>
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-toggle="collapse" href="#sideStudent">
					  <i class="material-icons">local_library</i>
					  <p> Student
						<b class="caret"></b>
					  </p>
					</a>
					<div class="collapse" id="sideStudent">
					  <ul class="nav">
						<li class="nav-item ">
						  <a class="nav-link" href="<?php echo base_url('Admin/studAdmission/old'); ?>">
							<span class="sidebar-mini"> OA </span>
							<span class="sidebar-normal"> Old-Admission </span>
						  </a>
						</li>
						<li class="nav-item ">
						  <a class="nav-link" href="<?php echo base_url('Admin/studAdmission/pre'); ?>">
							<span class="sidebar-mini"> SA </span>
							<span class="sidebar-normal"> Pre-Admission </span>
						  </a>
						</li>
						<li class="nav-item ">
						  <a class="nav-link" href="<?php echo base_url('Admin/studAdmission/final'); ?>">
							<span class="sidebar-mini"> SA </span>
							<span class="sidebar-normal"> Admission </span>
						  </a>
						</li>
						<li class="nav-item ">
						  <a class="nav-link" href="<?php echo base_url('Admin/studCourse'); ?>">
							<span class="sidebar-mini"> SC </span>
							<span class="sidebar-normal"> Student Courses</span>
						  </a>
						</li>
						<li class="nav-item ">
						  <a class="nav-link" href="<?php echo base_url('Admin/studMaster'); ?>">
							<span class="sidebar-mini"> SM </span>
							<span class="sidebar-normal"> Master Student</span>
						  </a>
						</li>
					  </ul>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo base_url('uploads/Help.pdf'); ?>" target="_blank">
					  <i class="material-icons">help</i>
					  Help
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="main-panel">
		<nav class="navbar navbar-expand-lg bg-white navbar-absolute fixed-top">
			<div class="container-fluid">
				<div class="navbar-wrapper">
					<a class="navbar-brand" href="<?php echo base_url('Subadmin'); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" class="img-responsive w-100" /></a><?php //echo $_SESSION['userData']['org_logo']; ?>
				</div>
				<div class="justify-content-end d-lg-none d-none d-lg-block d-xl-none d-none d-xl-block">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('Subadmin'); ?>">
							<i class="material-icons">dashboard</i>Dashboard
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('Subadmin/programMaster'); ?>">
							<i class="material-icons">article</i>Programs
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('Subadmin/teacherMaster'); ?>">
							<i class="material-icons">school</i>Teacher
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="javascript:;" id="navStudent" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="material-icons">local_library</i>Student
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navStudent">
								<a class="dropdown-item" href="<?php echo base_url('Admin/studAdmission/old'); ?>">Old-Admission</a>
								<a class="dropdown-item" href="<?php echo base_url('Admin/studAdmission/pre'); ?>">Pre-Admission</a>
								<a class="dropdown-item" href="<?php echo base_url('Admin/studAdmission/final'); ?>">Admission</a>
								<a class="dropdown-item" href="<?php echo base_url('Admin/studCourse'); ?>">Student Course</a>
								<a class="dropdown-item" href="<?php echo base_url('Admin/studMaster'); ?>">Master Student</a>
							</div>
						</li>
					</ul>
				</div>
				<button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
					<span class="sr-only">Toggle navigation</span>
					<span class="navbar-toggler-icon icon-bar"></span>
					<span class="navbar-toggler-icon icon-bar"></span>
					<span class="navbar-toggler-icon icon-bar"></span>
				</button>
				<div class="collapse navbar-collapse justify-content-end">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link" rel="tooltip" title="Message" href="<?php echo base_url('Teacher/message'); ?>">
							<i class="material-icons">email</i>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" rel="tooltip" title="Help" href="<?php echo base_url('uploads/Help.pdf'); ?>" target="_blank">
							<i class="material-icons">help_center</i>
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							  <img src="<?php echo base_url().$_SESSION['userData']['photo']; ?>" onerror="this.src='<?php echo base_url(); ?>assets/img/default-avatar.png'" class="avatar-dp" >
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile" style="min-width:15rem !important;">
							  <h6 class="text-center"><?php echo '<strong>'.$_SESSION['userData']['name'].'</strong><br>'.$_SESSION['userData']['email']; ?></h6>
							  <div class="dropdown-divider"></div>
							  <a class="dropdown-item" href="<?php echo base_url('Teacher'); ?>"><i class="material-icons">dashboard</i> Dashboard</a>
							  <a class="dropdown-item" href="<?php echo base_url().'Teacher/userProfile'; ?>"><i class="material-icons">account_circle</i> Profile</a>
							  <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#passModal"><i class="material-icons">settings</i> Settings</a>
							  <div class="dropdown-divider"></div>
							  <a class="dropdown-item" href="<?php echo base_url().'Teacher/logout'; ?>"><i class="fa fa-sign-out"></i> Log out</a>
							</div>
						  </li>
					</ul>
				</div>
			</div>
		</nav>
		
		<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Change Password</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<i class="material-icons">clear</i>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="new-pass" class="control-label">New Password</label>
									<input type="password" class="form-control" id="new-pass" required autocomplete="off">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="progress progress-striped active">
									<div class="progress-bar progress-bar-red" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" id="pass-progress" style="width: 0%"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group has-info" id="re-pass-div">
									<label for="re-pass" class="control-label">Re-enter Password</label>
									<input type="password" class="form-control" id="re-pass" required autocomplete="off">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-link" id="btn_save_pass">Save Password</button>
					  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<script src="<?php echo base_url('assets/js/passcommon.js'); ?>"></script>