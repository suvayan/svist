<?php

// Include Requests only if not already defined
if (class_exists('Requests') === false)
{
    require_once __DIR__.'/libs/Requests-1.7.0/library/Requests.php';
}

try
{
    Requests::register_autoloader();

    if (version_compare(Requests::VERSION, '1.6.0') === -1)
    {
        throw new Exception('Requests class found but did not match');
    }
}
catch (\Exception $e)
{
    throw new Exception('Requests class found but did not match');
}

spl_autoload_register(function ($class)
{
    // project-specific namespace prefix
    $prefix = 'Razorpay\Api';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0)
    {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    //
    // replace the namespace prefix with the base directory,
    // replace namespace separators with directory separators
    // in the relative class name, append with .php
    //
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file))
    {
        require $file;
    }
});

use Razorpay\Api\Api;

$keyId = $razorpay_keys['key_id'];
$secretKey = $razorpay_keys['key_secret'];
$api = new Api($keyId, $secretKey);

$razorpay_payment_id = $inputs['razorpay_payment_id'];
$razorpay_order_id = $inputs['razorpay_order_id'];
$razorpay_signature = $inputs['razorpay_signature'];

$payment = $api->payment->fetch($razorpay_payment_id);

	$data1['payment_id'] = $payment['id'];
	$data1['order_id'] = $payment['order_id'];
	$data1['signature_hash'] = $razorpay_signature;
	$data1['userid'] = $_SESSION['userData']['userId'];
	$data1['user_email'] = $_SESSION['userData']['email'];
	$data1['amount'] = $payment['amount'];
	$data1['status'] = $payment['status'];
	$data1['bank_name'] = $payment['bank'];
	$data1['resp_msg'] = $payment['error_description'];
	$data1['add_datetime'] = date('Y-m-d H:i:s');
	$data1['prog_id'] = $inputs['prog_id'];
	insertIntoTransation($data1);
	
	$data2['payment_done'] = $inputs['pamount'];
	$where = 'sl='.$inputs['spc_id'];
	updateStudProgCons($data2, $where);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="<?php echo base_url().'assets/img/favicon.png'; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Ongoing Payment | Student</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
  <!--   Core JS Files   -->
  <script src="<?php echo base_url(); ?>assets/js/core/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/core/popper.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-notify.js"></script>
  <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/jquery-ui.js"></script>  
  <script>var baseURL='<?php echo base_url(); ?>'; var uType = "<?php echo $_SESSION['userData']['utype']; ?>";</script>
  <style>
	.razorpay-payment-button {
		display: none;
	}
  </style>
</head>

<body>
<div class="preloader" id="loader">
	<img src="<?php echo base_url().'assets/img/Preloader_3.gif'; ?>">
</div>
<div class="wrapper ">
	<div class="main-panel">
		<div class="content">
			<div class="container">
				<?php if($payment['status']=='captured'){ ?>
				<div class="alert alert-success text-center">
				  <strong>Success!</strong>
				  <h3 class="font-weight-bold">Payment Successfull!</h3>
				  <h4 class="font-weight-bold text-center"><a href="<?php echo base_url('Student/requestPrograms'); ?>" class="btn btn-danger btn-sm">Go Back</a></h4>
				</div>
				<?php }else{ ?>
				<div class="alert alert-warning text-center">
				  <strong>Warning!</strong>
				  <h3 class="font-weight-bold">Payment Unsuccessfull! The amount will be refund within 4-5 days of bank hours.</h3>
				  <h4 class="font-weight-bold text-center"><a href="<?php echo base_url('Student/requestPrograms'); ?>" class="btn btn-danger btn-sm">Go Back</a></h4>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
	<script src="<?php echo base_url(); ?>assets/js/core/bootstrap-material-design.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.bootstrap-wizard.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.validate.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/vendor/owlcarousel/js/owl.carousel.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-selectpicker.js"></script>

	<script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap-tagsinput.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/plugins/arrive.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/plugins/sweetalert2.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/material-dashboard.min1c51.js?v=2.1.2" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>assets/demo/demo.js"></script>
	<script src="<?php echo base_url(); ?>assets/demo/jquery.sharrre.js"></script>
</body>

</html>