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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="<?php echo base_url(); ?>assets/css/material-dashboard.min1c51.css?v=2.1.2" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/demo/demo.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/vendor/owlcarousel/css/owl.carousel.min.css" rel="stylesheet">
  <!--   Core JS Files   -->
  <script src="<?php echo base_url(); ?>assets/js/core/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-notify.js"></script>
  <script>var baseURL='<?php echo base_url(); ?>';</script>
</head>

<body>
<div class="preloader" id="loader">
	<img src="<?php echo base_url().'assets/img/Preloader_3.gif'; ?>">
</div>
<div class="wrapper ">
	<div class="sidebar" data-color="purple" data-background-color="black" data-image="<?php echo base_url(); ?>assets/img/sidebar-1.jpg">
      <div class="sidebar-wrapper">
        <ul class="nav">
		  <li class="nav-item ">
			<a href="<?php echo base_url(); ?>" class="nav-link">
			  Home
			</a>
		  </li>
		  <li class="nav-item ">
			<a href="<?php echo base_url('programs'); ?>" class="nav-link">
			  Programs
			</a>
		  </li>
		  <li class="nav-item ">
			<a href="<?php echo base_url('contact'); ?>" class="nav-link">
			  Contact Us
			</a>
		  </li>
		  <li class="nav-item ">
			<a href="<?php echo base_url('register/student'); ?>" class="nav-link">
			  New Student Registeration
			</a>
		  </li>
        </ul>
      </div>
    </div>
	<div class="main-panel">
		<nav class="navbar navbar-expand-lg bg-white navbar-absolute fixed-top ">
        <div class="container">
		  <div class="navbar-wrapper">
            <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/img/logo.png" class="img-responsive" style="width:190%;"/></a>
          </div>

          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">

            <ul class="navbar-nav">
              <li class="nav-item ">
				<a href="<?php echo base_url(); ?>" class="nav-link text-dark">
				  Home
				</a>
			  </li>
			  <li class="nav-item ">
				<a href="<?php echo base_url('programs'); ?>" class="nav-link text-dark">
				  Programs
				</a>
			  </li>
			  <li class="nav-item ">
				<a href="<?php echo base_url('contact'); ?>" class="nav-link">
				  Contact Us
				</a>
			  </li>
			  <li class="nav-item ">
				<a href="<?=base_url('oldRegister'); ?>" class="nav-link">
				  Existing Student Registeration
				</a>
			  </li>
			</ul>
          </div>
        </div>
      </nav>