<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Login extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');
		$this->load->model('Course_model');
		$this->load->model('Member');
		$this->load->model('Exam_model');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
	}
	/********************************MENU*********************************/
	public function index()
	{
		$this->global['pageTitle'] = 'Login | SVIST';
		$data['orgs'] = $this->LoginModel->getAllOrganizations();
		$data['strms'] = $this->LoginModel->getAllStreamsByOrgId('1');
		$this->loadViews("index", $this->global, $data, NULL);
	}
	
	public function register($type)
	{
		$this->global['pageTitle'] = 'Registration | SVIST';
		$this->global['userType'] = $type;
		$data['orgs'] = $this->LoginModel->getAllOrganizations();
		$data['strms'] = $this->LoginModel->getAllStreamsByOrgId('1');
		$this->loadViews("register", $this->global, $data, NULL);
	}
	
	public function portfolio()
	{
		$this->global['pageTitle'] = 'Portfolio | SVIST';
		$this->loadViews("portfolio", $this->global, NULL, NULL);
	}
	
	public function programs()
	{
		$this->global['pageTitle'] = 'Programs | SVIST';
		$this->loadViews("programs", $this->global, NULL, NULL);
	}
	
	public function programAdmission()
	{
		$this->global['pageTitle'] = 'Program Admission | SVIST';
        $data['prog_id'] = base64_decode($_GET['id']);
		$data['prog'] = $this->LoginModel->getProgramById($data['prog_id']);
		$data['degree'] = $this->LoginModel->getAllDegrees();
		$data['streams'] = $this->LoginModel->getAllStreams();
		$this->loadViews("program-admission", $this->global, $data, NULL);
	}
	
	public function requestDemo()
	{
		$this->global['pageTitle'] = 'Request for Demo | SVIST';
		
		$this->loadViews("request-demo", $this->global, NULL, NULL);
	}
	
	public function contact()
	{
		$this->global['pageTitle'] = 'Contact Us | SVIST';
		
		$this->loadViews("contact", $this->global, NULL, NULL);
	}
	
	public function errors_404()
	{
		$this->isLoggedIn();
		$this->global['pageTitle'] = 'SVIST | 404 - Page Not Found';
        
        $this->loadViews("errors_404", $this->global, NULL, NULL);
	}
	
	public function resetPassword()
	{
		$this->global['email'] = base64_decode($_GET['email']);
		$vcode = base64_decode($_GET['vercode']);
		$chkStatus = $this->LoginModel->checkChngPassStat($this->global['email'], $vcode);
		if($chkStatus[0]->flag!='f'){
			$this->session->set_flashdata('error', 'The link is already used, resend the lost password.');
			redirect(base_url().'login');
		}else{
			$this->global['pageTitle'] = 'SVIST | Password Reset';
			$this->loadViews("password-reset", $this->global);
		}
	}
	
	/********************************LOGIN********************************/
	public function loginMe()
	{
		$resp = array('accessGranted' => false, 'utype'=>"", 'errors' => '');
		$strm = [];
		$email = strtolower(trim($_POST['username']));
		$password = md5(trim($_POST['password']));
		$grespond = $_POST['g-recaptcha-response'];
		if($grespond!=NULL){
			if($this->validCaptcha($grespond)){
				$user = $this->LoginModel->checkValidUser($email);
				if(count($user)>0){
					if($user[0]->verification_status!='f'){
						if($user[0]->password==$password){
							/*$streams = $this->LoginModel->getUsersDepts($user[0]->user_id);
							foreach($streams as $row)
							{
								array_push($strm, $row->strm_id);
							}*/
							if (!empty($_SERVER['HTTP_CLIENT_IP']))   
							{
								$ip_address = $_SERVER['HTTP_CLIENT_IP'];
							}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
							{
								$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
							}else
							{
								$ip_address = $_SERVER['REMOTE_ADDR'];
							}
							//echo $ip_address;
							$data['user_id'] = $user[0]->user_id;
							$data['user_email'] = $user[0]->email;
							$data['login_datetime'] = date('Y-m-d H:i:s');
							$data['ipaddress'] = trim($ip_address);
							$data['status'] = 1;
							$ltId = $this->LoginModel->insertDataRetId('login_track', $data);
							
							$name = trim($user[0]->first_name." ".$user[0]->last_name);
							$utype = trim($user[0]->user_type);
							$userdata = array(
								'userId'=>$user[0]->user_id,
								'name'=>$name,
								'email'=>trim($user[0]->email),
								'mobile'=>trim($user[0]->phone),
								'photo'=>trim($user[0]->photo_sm),
								/*'org'=>(($utype=='admin')? '':$streams[0]->org_id),
								'streams'=>(($utype=='admin')? '':$strm),*/
								'utype'=>$utype,
								'lt_id'=>$ltId,
								'isLoggedIn'=>true
							);
							if($utype == 'subadmin'){
								$userdata['org_id'] = trim($user[0]->org_id);
								$userdata['org_logo'] = trim($user[0]->org_logo);
							}
							$this->session->set_userdata('userData', $userdata);
							$resp['accessGranted'] = true;
							$resp['utype'] = ucfirst($utype);
						}else{
							$resp['errors'] = 'Password did not matched. Please try again.';
						}
					}else{
						$resp['errors'] = 'Please verify your email. Find verification link in your email.';
					}
				}else{
					$resp['errors'] = 'User does not exit. Please register.';
				}
			}else{
				$resp['errors'] = 'Invalid reCaptcha. Try again.';
			}
		}else{
			$resp['errors'] = 'Must check reCaptcha.';
		}
		
		echo json_encode($resp);
	}
	public function userProgLogin()
	{
		$resp = array('accessGranted' => false, 'utype'=>"", 'errors' => '');
		$strm = [];
		$email = strtolower(trim($_POST['username']));
		$password = md5(trim($_POST['password']));
		$prog_id = trim($_POST['prog_id']);
		$user = $this->LoginModel->checkValidUser($email);
		if(count($user)>0){
			if($user[0]->verification_status!='f'){
				if($user[0]->password==$password){
					$streams = $this->LoginModel->getUsersDepts($user[0]->user_id);
					foreach($streams as $row)
					{
						array_push($strm, $row->strm_id);
					}
					if (!empty($_SERVER['HTTP_CLIENT_IP']))   
					{
						$ip_address = $_SERVER['HTTP_CLIENT_IP'];
					}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
					{
						$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}else
					{
						$ip_address = $_SERVER['REMOTE_ADDR'];
					}
					//echo $ip_address;
					$data['user_id'] = $user[0]->user_id;
					$data['user_email'] = $user[0]->email;
					$data['login_datetime'] = date('Y-m-d H:i:s');
					$data['ipaddress'] = trim($ip_address);
					$data['status'] = 1;
					$ltId = $this->LoginModel->insertDataRetId('login_track', $data);
					
					$name = trim($user[0]->first_name." ".$user[0]->last_name);
					$utype = trim($user[0]->user_type);
					$userdata = array(
						'userId'=>$user[0]->user_id,
						'name'=>$name,
						'email'=>trim($user[0]->email),
						'mobile'=>trim($user[0]->phone),
						'photo'=>trim($user[0]->photo_sm),
						'org'=>(($utype=='admin')? '':$streams[0]->org_id),
						'streams'=>(($utype=='admin')? '':$strm),
						'utype'=>$utype,
						'lt_id'=>$ltId,
						'isLoggedIn'=>true
					);
					$this->session->set_userdata('userData', $userdata);
					
					$prog = $this->LoginModel->getProgramById($prog_id);
					$uprogset = array('userId'=>$user[0]->user_id,'progId'=>$prog_id, 'progName'=>trim($prog[0]->title), 'status'=>false);
					set_cookie('userProgram', json_encode($uprogset), 5*60);
					
					$resp['accessGranted'] = true;
					$resp['utype'] = ucfirst($utype);
				}else{
					$resp['errors'] = 'Password did not matched. Please try again.';
				}
			}else{
				$resp['errors'] = 'Please verify your email. Find verification link in your email.';
			}
		}else{
			$resp['errors'] = 'User does not exit. Please register.';
		}
		echo json_encode($resp);
	}
	public function updatePassword()
	{
		$resp = array('accessGranted' => false, 'errors' => '');
		
		$vcode = trim($_POST['vcode']);
		$user_id = trim($_POST['email']);
		$password = md5(trim($_POST['password']));
		
		$data1['flag'] = true;
		$where1 = 'user_id='.$user_id.' AND verification_code='.$vcode;
		
		$data2['password'] = $password;
		$data2['modified_date_time'] = date('Y-m-d H:i:s');
		$where2 = 'user_id='.$user_id;
		$grespond = $_POST['g-recaptcha-response'];
		if($grespond!=NULL){
			if($this->validCaptcha($grespond)){
				if($this->LoginModel->updateData('b2c_verification_code', $data1, $where1)){
					$this->LoginModel->updateData('user_auth', $data2, $where2);
					$this->session->set_flashdata('error', 'Password has been changed, please log in.');
					$resp['accessGranted'] = true;
				}else{
					$this->session->set_flashdata('error', 'Password update failed, Server Error!!!. Please try again');
					$resp['errors'] = 'Password update failed, Server Error!!!. Please try again';
				}
			}else{
				$resp['errors'] = 'Invalid reCaptcha. Try again.';
			}
		}else{
			$resp['errors'] = 'Must check reCaptcha.';
		}
		
		echo json_encode($resp);
	}

	/********************************REGISTER*****************************/
	public function userAuthentication()
	{
		$resp = array('accessGranted' => false, 'errors' => '');
		$this->load->helper('string');
		$vcode = random_string('numeric', 6);
		$grespond = $_POST['g-recaptcha-response'];
		$weblink = '';
		
		$org_id = $_POST['org']; 
		$streams = $_POST['dept']; 
		$scount = count($streams);
		$fname = ucwords(strtolower(trim($_POST['fname'])));
		$lname = ucwords(strtolower(trim($_POST['lname'])));
		$email = strtolower(trim($_POST['email']));
		$password = trim($_POST['passwd']);
		$phone = trim($_POST['phone']);
		$type = trim($_POST['user_type']);
		/*if($type=='teacher'){
			$data['organization'] = trim($_POST['org']);
			//$data['designation'] = ucwords(strtolower(trim($_POST['designation'])));
			//$data['website'] = strtolower(trim($_POST['weblink']));
		}*/

		$subject = 'SVIST Registration';
		$msg = '<html><body>Hello '.trim($fname." ".$lname).',<br><br><h4 style="text-align:center">Thank you for Signing Up at svist.billionskills.com</h4><hr><br>Please click <a href="'.base_url().'Login/userVerification/?email='.base64_encode($email).'&vercode='.base64_encode($vcode).'" target="_blank">here</a> to verify your email and complete your registration.<hr></body></html>';
		if($grespond!=NULL){
			if($this->validCaptcha($grespond)){
				if($email!=null){
					$user = $this->LoginModel->checkValidUser($email);
					if(count($user)<=0){
						//$this->MailSystem($email, '', $subject, $msg)
						if(true){
							$data['first_name'] = trim($fname);
							$data['last_name'] = trim($lname);
							$data['email'] = $email;
							$data['phone'] = $phone;
							$data['created_date_time'] = date('Y-m-d H:i:s');
							
							$userid = $this->LoginModel->insertUserDRetId($data);
							if($userid){
								$data1['user_id'] = $userid;
								$data1['first_name'] = trim($fname);
								$data1['last_name'] = trim($lname);
								$data1['phone'] = $phone;
								$data1['email'] = $email;
								$data1['password'] = md5($password);
								$data1['status'] = true;
								$data1['verification_code'] = $vcode;
								$data1['verification_status'] = true;
								$data1['user_type'] = $type;
								$data1['create_date_time'] = date('Y-m-d H:i:s');
								$this->LoginModel->insertUserAData($data1);
								
								for($i=0; $i<$scount; $i++){
									$data2[$i]['org_id'] = $org_id;
									$data2[$i]['strm_id'] = $streams[$i];
									$data2[$i]['user_id'] = $userid;
									$data2[$i]['add_datetime'] = date('Y-m-d H:i:s');
									$this->LoginModel->insertData('org_map_users', $data2[$i]);
								}
								$resp['accessGranted'] = true;
							}
						}else{
							$resp['errors'] = 'Registration error, please try again.';
						}
					}else{
						$resp['errors'] = '<br>The email address already exits.';
					}
				}else{
					$resp['errors'] = 'Fields are empty.';
				}
			}else{
				$resp['errors'] = 'Invalid reCaptcha. Try Again.';
			}
		}else{
			$resp['errors'] = 'Must check reCaptcha.';
		}
		
		echo json_encode($resp);
	}
	public function userVerification()
	{
		$email = base64_decode($_GET['email']);
		$vercode = base64_decode($_GET['vercode']);
		$check = $this->LoginModel->checkVerifuStatus($email, $vercode);
		if($check!=1){
			if($this->LoginModel->updateVerifyStatus($email, $vercode)){
				$this->session->set_flashdata('success', 'Your registration has complete, please login.');
			}else{
				$this->session->set_flashdata('error', 'The email verification failed, please try again.');
			}
		}else{
			$this->session->set_flashdata('error', 'The email is already verified, please login.');
		}
		
		redirect(base_url());
	}
	/*===================================================================*/
	public function getDepartments()
	{
		$org = $_GET['org'];
		$output = '<option value="">Department <span class="text-danger">*</span></option>';
		$strms = $this->LoginModel->getAllStreamsByOrgId($org);
		
		if(!empty($strms)){
			foreach($strms as $row){
				$output.= '<option value="'.$row->id.'">'.trim($row->title).' ('.trim($row->short_name).')</option>';
			}
		}
		echo $output;
	}
	public function getPrograms()
	{
		$dept = $_GET['dept'];
		$acayear = $_GET['acayear'];
		$output = '<option value="">Programs <span class="text-danger">*</span></option>';
		$progs = $this->LoginModel->getAllProgramsByDeptId($dept, $acayear);
		
		if(!empty($progs)){
			foreach($progs as $row){
				$output.= '<option value="'.$row->id.'">'.trim($row->title).' ('.trim($row->yearnm).')</option>';
			}
		}
		echo $output;
	}
	public function getSemesters()
	{
		$prog = $_GET['prog'];
		$output = '<option value="">Semester <span class="text-danger">*</span></option>';
		$sems = $this->LoginModel->getAllSemsByProgId($prog);
		
		if(!empty($sems)){
			foreach($sems as $row){
				$output.= '<option value="'.$row->id.'">'.trim($row->title).'</option>';
			}
		}
		echo $output;
	}
	public function oldRegister()
	{
		$this->global['pageTitle'] = 'Existing Student Register | SVIST';
		$data['orgs'] = $this->LoginModel->getAllOrganizations();
		$data['acayear'] = $this->LoginModel->getAllAcayear();
		$this->loadViews("old-register", $this->global, $data, NULL);
	}
	public function oldstudregister()
	{
		$userid = 0;$msg='';
		$this->load->helper('string');
		$vcode = random_string('numeric', 6);
		$grespond = $_POST['g-recaptcha-response'];
		if($grespond!=NULL){
			if($this->validCaptcha($grespond)){
				$org_id = $_POST['org']; 
				$streams = $_POST['dept'];
				$pid = $_POST['prog'];				
				$sem_id = $_POST['sem'];
				$fname = ucwords(strtolower(trim($_POST['fname'])));
				$lname = ucwords(strtolower(trim($_POST['lname'])));
				$email = strtolower(trim($_POST['email']));
				$password = trim($_POST['passwd']);
				$phone = trim($_POST['phone']);
				
				$prog = $this->Member->getProgramById($pid);
				$cids = $this->Member->getCourseIdsByProgid($pid, $sem_id);
				
				$user = $this->LoginModel->checkValidUser($email);
				if(empty($user)){
					$subject = 'SVIST Registration';
					$message = '<html><body>Hello '.trim($fname." ".$lname).',<br><br><h4 style="text-align:center">Thank you for Signing Up at svist.billionskills.com</h4><hr><br>Please click <a href="'.base_url().'Login/userVerification/?email='.base64_encode($email).'&vercode='.base64_encode($vcode).'" target="_blank">here</a> to verify your email and complete your registration.<hr></body></html>';
					$this->MailSystem($email, '', $subject, $message);
					$data['first_name'] = trim($fname);
					$data['last_name'] = trim($lname);
					$data['email'] = $email;
					$data['phone'] = $phone;
					$data['created_date_time'] = date('Y-m-d H:i:s');
					$userid = $this->LoginModel->insertUserDRetId($data);
					if($userid!=0){
						$data1['user_id'] = $userid;
						$data1['first_name'] = trim($fname);
						$data1['last_name'] = trim($lname);
						$data1['phone'] = $phone;
						$data1['email'] = $email;
						$data1['password'] = md5($password);
						$data1['status'] = true;
						$data1['verification_code'] = $vcode;
						$data1['verification_status'] = false;
						$data1['user_type'] = 'student';
						$data1['create_date_time'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertUserAData($data1);
						
						$data2['org_id'] = $org_id;
						$data2['strm_id'] = $streams;
						$data2['user_id'] = $userid;
						$data2['add_datetime'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertData('org_map_users', $data2);
					}
					$msg.= 'Your account has been created. Please check your mail for verification.'; 
				}else{
					$userid = $user[0]->user_id;
					$msg.= 'Your account alreay exist.';
				}
				$chk_adm = $this->Member->checkReduntAdmission($pid, $userid);
				if($chk_adm==0){
					$data3['prog_id'] = $pid;
					$data3['cand_id'] = $userid;
					$data3['approve_flag'] = 0;
					$data3['prog_status'] = 0;
					$data3['apply_datetime'] = date('Y-m-d H:i:s');
					$this->LoginModel->insertUserAdmission($data3);
					$msg.= '<br>Your application has been made.';
				}else{
					$msg.= '<br>Your application already exist for the program.';
				}
				$chk_spc = $this->Member->getUserSPCData($userid, $pid);
				if(empty($chk_spc)){
					$data4['stud_id'] = $userid;
					$data4['prog_id'] = $pid;
					$data4['org_id'] = $org_id;
					$data4['stream_id'] = $streams;
					$data4['enrollment_no'] = $_POST['enroll'];
					$data4['status'] = 0;
					$data4['aca_yearid'] = $prog[0]->aca_year;
					$data4['add_datetime'] = date('Y-m-d H:i:s');
					$spc_id = $this->Exam_model->insertDataRetId('stud_prog_cons', $data4);
					
					$data5['stud_id'] = $userid;
					$data5['spc_id'] = $spc_id;
					$data5['stream_id'] = $streams;
					$data5['sem_id'] = $_POST['sem'];
					$data5['roll_no'] = trim($_POST['rollno']);
					$data5['aca_year'] = substr($prog[0]->yearnm, 0, 4);
					$data5['status'] = true;
					$data5['add_date'] = date('Y-m-d H:i:s');
					$sps_id = $this->Exam_model->insertDataRetId('stud_prog_state', $data5);
					if(!empty($cids)){
						$i=1;
						foreach($cids as $crow){
							$data6[$i]['sps_id'] = $sps_id;
							$data6[$i]['course_id'] = $crow->course_id;
							$data6[$i]['add_date'] = date('Y-m-d H:i:s');
							$this->Exam_model->insertData('stud_prog_course', $data6[$i]);
							$i++;
						}
					}
				}
				$msg.= '<br>Please await for your approval from the Admin. You can check your program status in your dashboard after login.';
				$this->session->set_flashdata('error', $msg);
			}else{
				$this->session->set_flashdata('error', 'Invalid reCaptcha. Try Again.');
			}
		}else{
			$this->session->set_flashdata('error', 'Must check reCaptcha.');
		}
		redirect(base_url().'oldRegister');
	}
	
	/********************************CRUD*********************************/
	public function receiveRequestDemo()
	{
		$resp = array('accessGranted' => false, 'errors' => '');
		
		$data['first_name'] = ucwords(strtolower(trim($_POST['fname'])));
		$data['last_name'] = ucwords(strtolower(trim($_POST['lname'])));
		$data['email'] = strtolower(trim($_POST['email']));
		$data['phone'] = trim($_POST['phone']);
		$data['job_title'] = ucwords(strtolower(trim($_POST['job_title'])));
		$data['company'] = trim($_POST['company']);
		$data['description'] = trim($_POST['desc']);
		$data['res_datetime'] = date('Y-m-d H:i:s');
		$data['rd_id'] = 'MLP_'.$data['first_name'][0].$data['last_name'][0].date('YmdHi');
		
		$subject = 'SVIST Learning Request for demo';
		$msg = '<html><body>Hello '.trim($data['first_name']." ".$data['last_name']).',<br><br>Your request for demo has been received.<br>This is your request demo code: <strong>'.$data['rd_id'].'</strong>.<br>Please remember this code for further notice. We will get in touch with you within a period of time.<br><hr>
		<code>This is an autogenerated message. Please do not reply.</code></body></html>';
		
		if($this->MailSystem($email, '', $subject, $msg)){
			$this->LoginModel->insertData('request_demo', $data);
			$resp['accessGranted'] = true;
		}else{
			$resp['errors'] = 'Oops! Something went wrong. Please try again.';
		}
		echo json_encode($resp);
	}
	public function getProgramsFilter()
	{
		$ptitle = trim($_POST['title']);
		$cat = (isset($_POST['pcat']))? $_POST['pcat']: "";
		$i=1;
		$data['program'] = $this->LoginModel->getProgramsFiltered($ptitle, $cat);
		foreach($data['program'] as $row){
			$pid = $row->id;
			$data['ins_'.$i] = $this->Member->getProgramOrg($pid);
			$i++;
		}
		
		echo $this->load->view('home/programs_list', $data, true);
	}
	public function programDetails()
	{
		$i=1;
		$this->global['pageTitle'] = 'Program Details | SVIST';
        $data['prog_id'] = base64_decode($_GET['id']);
		$data['prog'] = $this->LoginModel->getProgramById($data['prog_id']);
		$data['institute'] = $this->Member->getProgramOrg($data['prog_id']);
		$data['stream'] = $this->Member->getProgramStreams($data['prog_id']);
		$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', null, 'accepted');
		$data['sems'] = $this->LoginModel->getAllSemsByProgId($data['prog_id']);
		$countsms = count($data['sems']);
		if($countsms<=1){
			$data['procourse'] = $this->Course_model->getProgramCourses($data['prog_id']);
		}else{
			
			foreach($data['sems'] as $row){
				$sem_id = $row->id;
				$data['procourse_'.$i] = $this->LoginModel->getProgSemCourses($sem_id, $data['prog_id']);
				$i++;
			}
		}
		$this->loadViews("program-details", $this->global, $data, NULL);
	}
	public function userAdmission()
	{
		$resp = array('status' => 'error', 'title' => '', 'msg'=>'');
		$this->load->helper('string');
		$vcode = random_string('numeric', 6);
		
		$fname = ucwords(strtolower(trim($_POST['fname'])));
		$lname = ucwords(strtolower(trim($_POST['lname'])));
		$email = strtolower(trim($_POST['email']));
		$password = trim($_POST['newpass']);
		$phone = trim($_POST['phone']);
		$type = 'student';
		$ecount = trim($_POST['educount']);
		$pid = trim($_POST['pid']);
		$pdetails = $this->LoginModel->getProgramInfoById($pid);
		$apply_type = (int)$pdetails[0]->apply_type;
		
		$subject = 'SVIST Learning Registration';
		$msg = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="content-type" content="text/html;charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /><style>*{margin:0;padding:0;}body {font-size:14px;font-family: monospace;}table, th, td {border: 1px solid black;border-collapse: collapse;}th {text-align: left;}th, td {padding: 15px;}.container {width: 90%;margin: 0 auto;padding: 3rem 2rem;}</style></head><body><div class="container">Hello '.trim($fname." ".$lname).',<br><h4 style="text-center">Thank you for your registration in SVIST.org</h4>Your application has been '.(($apply_type==0)? 'approved':'saved').'. You application is as follow:<br><br><table width="100%"><tr><th width="20%">Program</th><td width="0%">'.trim($pdetails[0]->title).'</td></tr><tr><th width="20%">Duration</th><td width="0%">'.trim($pdetails[0]->duration).' '.trim($pdetails[0]->dtype).'(s)</td></tr><tr><th width="20%">Application Start Date</th><td width="0%">'.date('jS M Y',strtotime($pdetails[0]->start_date)).'</td></tr><tr><th width="20%">Program Administrator</th><td width="0%">'.trim($pdetails[0]->uname).'</td></tr><tr><th width="20%">Program details</th><td width="0%">For more details of the program, Please | <a href="'.base_url('programDetails/?id='.base64_encode($pdetails[0]->id)).'" target="_blank">Click Here</a></td></tr></table><br><br>Your Application is half way done. Please verify your email <a href="'.base_url().'Login/userVerification/?email='.base64_encode($email).'&vercode='.base64_encode($vcode).'" target="_blank">here</a> before you can login to your dashboard and '.(($apply_type==0)? 'access the program':'complete your admission procedure').'.<br><br><br>Thanking you,<br>SVIST.org,<br>Learning and Course Management Platform</div></body></html>';
		
		if($email!=null){
			$user = $this->LoginModel->checkValidUser($email);
			if(count($user)<=0){
				if($this->MailSystem($email, '', $subject, $msg)){
					$data['first_name'] = trim($fname);
					$data['last_name'] = trim($lname);
					$data['email'] = $email;
					$data['phone'] = $phone;
					$data['gender'] = $_POST['gender'];
					$data['dateofbirth'] = date('Y-m-d',strtotime($_POST['dob']));
					$data['created_date_time'] = date('Y-m-d H:i:s');
					
					$userid = $this->LoginModel->insertUserDRetId($data);
					if($userid){
						$data1['user_id'] = $userid;
						$data1['first_name'] = trim($fname);
						$data1['last_name'] = trim($lname);
						$data1['phone'] = $phone;
						$data1['email'] = $email;
						$data1['password'] = md5($password);
						$data1['verification_code'] = $vcode;
						$data1['verification_status'] = false;
						$data1['user_type'] = $type;
						$data1['create_date_time'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertUserAData($data1);
						
						$data4['org_id'] = $pdetails[0]->org_id;
						$data4['strm_id'] = $pdetails[0]->stream_id;
						$data4['user_id'] = $userid;
						$data4['add_datetime'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertData('org_map_users', $data4);
						
						for($i=0; $i<=$ecount; $i++){
							$data2[$i]['user_id'] = $userid;
							$data2[$i]['board'] = strtoupper(trim($_POST['board_'.$i]));
							$data2[$i]['organization'] = ucfirst(strtolower(trim($_POST['org_'.$i])));
							$data2[$i]['degree_id'] = trim($_POST['degree_'.$i]);
							$data2[$i]['aca_status'] = (isset($_POST['status_'.$i]))? 'Completed' : 'Present';
							if($data2[$i]['aca_status']=='Completed'){
								$data2[$i]['passout_year'] = trim($_POST['passout_'.$i]);
								$data2[$i]['marks_per'] = trim($_POST['marks_'.$i]);
							}
							$data2[$i]['create_date_time'] = date('Y-m-d H:i:s');
							
							if($data2[$i]['board']!=""){
								$this->LoginModel->insertUserAcademic($data2[$i]);
							}
						}
						
						$data3['prog_id'] = $pid;
						$data3['cand_id'] = $userid;
						$data3['approve_flag'] = 0;
						$data3['prog_status'] = 0;
						$data3['apply_datetime'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertUserAdmission($data3);
						
						$resp = array('status' => 'success', 'title' => 'Successful', 'msg'=>'Your application has been accepted. Please wait for approval. Check your mail for further details.');
					}
				}else{
					$resp = array('status' => 'error', 'title' => 'Error', 'msg'=>'Unable to mail. Something went wrong. Please try again.');
				}
			}else{
				$resp = array('status' => 'warning', 'title' => 'Existing', 'msg'=>'This email is already registered. Please log in.');
			}
		}else{
			$resp = array('status' => 'error', 'title' => 'Error', 'msg'=>'Something went wrong.');
		}
		
		echo json_encode($resp);
	}
	
	/*public function inviteRespondStatus()
	{
		$userid = 0;
		$this->load->helper('string');
		$vcode = random_string('numeric', 6);
		$lname = '';
		$email = trim(base64_decode($_GET['email']));
		$prog_id = trim(base64_decode($_GET['prog']));
		$role = trim($_GET['role']);
		$stat = trim($_GET['status']);
		$user = $this->LoginModel->checkVerifyUser($email);
		$pdetails = $this->LoginModel->getProgramInfoById($prog_id);
		$ivnd = $this->Member->checkValidInvite($prog_id, $email, $role);
		$cids = $this->Member->getCourseIdsByProgid($prog_id);
		$name = explode(" ",trim($ivnd[0]->fullname));
		$ftype = trim($pdetails[0]->feetype);
		
		$subject = 'SVIST Learning Invitation Status';
		$message = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="content-type" content="text/html;charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /><style>*{margin:0;padding:0;}body {font-size:14px;font-family: monospace;}table, th, td {border: 1px solid black;border-collapse: collapse;}th {text-align: left;}th, td {padding: 15px;}.container {width: 90%;margin: 0 auto;padding: 3rem 2rem;}</style></head><body><div class="container">Hello '.trim($ivnd[0]->fullname).'<br>You have successfull '.$stat.' the invitation from '.trim($pdetails[0]->uname).' for the program: '.trim($pdetails[0]->title).' as a '.$role.'<br><br>';
		
		if(trim($ivnd[0]->status)=='pending'){
			$data['status'] = $stat;
			$data['respond_datetime'] = date('Y-m-d H:i:s');
			$where = "program_id=".$prog_id." and user_email='".$email."'";
			$this->LoginModel->updateData('pro_user_invite', $data, $where);
			if($stat=='accepted'){
				if(!empty($user)){
					//Existing User
					$userid = $user[0]->user_id;
				}else{
					//New User
					$userid = $this->createNewUser($name, $ivnd, $vcode);
					$message.='Your account has been created. Please verify your email <a href="'.base_url().'Login/userVerification/?email='.base64_encode($email).'&vercode='.base64_encode($vcode).'" target="_blank">here</a> before you can log in to your dashboard.<br><br>';
				}
				if($userid!=0){
					$chkUserRole = $this->Member->checkUserRoleOnProgram($prog_id, $role, $userid);
					if($chkUserRole<=0){
						if($role=='Teacher'){
							$data1['program_id'] = $prog_id;
							$data1['role'] = $role;
							$data1['status'] = $stat;
							$data1['user_id'] = $userid;
							$data1['add_date'] = date('Y-m-d H:i:s');
							$data1['status_ch_date'] = date('Y-m-d H:i:s');
							$this->LoginModel->insertData('pro_users_role', $data1);
						}else if($role=='Student'){
							$chkadm = $this->Member->checkRedundantADM($prog_id, $userid);
							if($chkadm<=0){
								$data1['program_id'] = $prog_id;
								$data1['role'] = $role;
								$data1['status'] = $stat;
								$data1['user_id'] = $userid;
								$data1['add_date'] = date('Y-m-d H:i:s');
								$data1['status_ch_date'] = date('Y-m-d H:i:s');
								
								$data2['prog_id'] = $prog_id;
								$data2['cand_id'] = $userid;
								$data2['prog_status'] = 0;
								$data2['apply_datetime'] = date('Y-m-d H:i:s');
								$data2['payment_status'] = false;
								
								$data3['stud_id'] = $userid;
								$data3['prog_id'] = $prog_id;
								$data3['aca_yearid'] = $ivnd[0]->acayear_id;
								$data3['admission_date'] = date('Y-m-d H:i:s');
								$data3['status'] = 0;
								$data3['enrollment_no'] = trim($ivnd[0]->enrollment_no);
								$data3['totalfees'] = (int)trim($pdetails[0]->total_fee);
								$data3['discount'] = (int)trim($pdetails[0]->discount);
								$data3['add_datetime'] = date('Y-m-d H:i:s');
								if($ftype=='Paid'){
									$data2['approve_flag'] = '1';
									$this->LoginModel->insertData('adm_can_apply', $data2);
									$this->LoginModel->insertData('stud_prog_cons', $data3);
									$message.='The program fee is Rs. '.trim($pdetails[0]->total_fee).(($pdetails[0]->discount!=0)? ' with discount of '.$pdetails[0]->discount.'%' : '.').'<br>Please pay the amount and await for final approval.<br>';
								}else{
									$data2['approve_flag'] = '2';
									$this->LoginModel->insertData('pro_users_role', $data1);
									$this->LoginModel->insertData('adm_can_apply', $data2);
									$spc_id = $this->LoginModel->insertDataRetId('stud_prog_cons', $data3);
									
									$data4['stud_id'] = $userid;
									$data4['spc_id'] = $spc_id;
									$data4['roll_no'] = trim($ivnd[0]->roll_no);
									$data4['aca_year'] = date('Y');
									$data4['status'] = true;
									$data4['add_date'] = date('Y-m-d H:i:s');
									$sps_id = $this->LoginModel->insertDataRetId('stud_prog_state', $data4);
									if(!empty($cids)){
										$j=1;
										foreach($cids as $crow){
											$data5[$j]['sps_id'] = $sps_id;
											$data5[$j]['course_id'] = $crow->course_id;
											$data5[$j]['add_date'] = date('Y-m-d H:i:s');
											$this->LoginModel->insertData('stud_prog_course', $data5[$j]);
											$j++;
										}
									}
									$message.='Please log in to your dashboard and access the program utilities.';
								}
							}else{
								$data0['msg'] = 'You have already applied in the program.';
							}
						}
					}else{
						$data0['msg'] = 'You are already in the program.';
					}
				}
				$message.= '<br><br><br>Thanking you,<br><img src="https://learn.techmagnox.com/assets/img/logo.png" style="width:100px;"/><br>Magnox Learning Plus,<br>Learning and Course Management Platform</div></body></html>';
			
				$this->MailSystem($email, '', $subject, $message);
				$data0['msg'] = 'Your respond has been saved. Please check your email for further details.<br><code>Check your spam, if not found in inbox.</code>';
			}else if($stat=='rejected'){
				$data0['msg'] = 'You have successfull rejected the invitation.';
			}
		}else{
			$data0['msg'] = 'You have already responded to this invitation.';
		}
		$this->load->view("home/landing-page", $data0);
	}*/
	/********************************INTEGRATED***************************/
	public function createNewUser($name, $ivnd, $vcode)
	{
		$userid = 0;
		$ncount = count($name);
				
		$data['first_name'] = trim($name[0]);
		for($i=1; $i<$ncount; $i++){
			$lname.= trim($name[$i])." ";
		}
		$data['last_name'] = trim($lname);
		$data['email'] = $ivnd[0]->user_email;
		$data['phone'] = $ivnd[0]->phone;
		$data['created_date_time'] = date('Y-m-d H:i:s');
		
		$userid = $this->LoginModel->insertUserDRetId($data);
		if($userid!=0){
			$data1['user_id'] = $userid;
			$data1['first_name'] = trim($name[0]);
			$data1['last_name'] = trim($lname);
			//$data1['gender'] = $gender;
			$data1['phone'] = $ivnd[0]->phone;
			$data1['email'] = $ivnd[0]->user_email;
			$data1['password'] = md5($vcode);
			$data1['verification_code'] = $vcode;
			$data1['verification_status'] = false;
			$data1['user_type'] = strtolower($role);
			$data1['create_date_time'] = date('Y-m-d H:i:s');
			$this->LoginModel->insertUserAData($data1);
		}
		return $userid;
	}
	public function validCaptcha($captcha)
	{
		//$captcha = $this->input->post('g-recaptcha-response');
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LcAodEZAAAAANsalr7vBuB0aJvr6xy-uz84aVjN&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
        if ($response . 'success' == false) {
            return FALSE;
        } else {
            return TRUE;
        }
	}
	public function SendMailOTPConfirmation()
	{
		$response = array(
				'status'=>'error',
				'title'=>'Oops',
				'email'=>"", 
				'code'=>"",
				'msg'=>'Something went wrong!'
			);
		$this->load->helper('string');
		$code = random_string('numeric', 6);
		
		$value = trim($_GET['qemail']);
		
		if($value!=""){
			if(preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $value)){
				$user = $this->LoginModel->checkVerifyUser($value);
				if(count($user)==1){
					$phone = trim($user[0]->phone);
					$msg = '<html>
							<body>
							Respected '.$user[0]->first_name.',
							<p style="text-align:justify;margin-left:10px;">
								We recently received a request to recover
								the Magnox [SVIST] account: '.$value.'.<br>Please click on <a
								href="'.base_url().'Login/resetPassword/?email='.base64_encode($user[0]->user_id).'&vercode='.base64_encode($code).'"
								target="_blank">This Link</a> or enter the code: '.$code.' in the reset password page to change it.
								<br>
								</p>
								<code style="font-weight: 600;">[This is a computer generated email. ]</code>
							</body>
						</html>';
					
					$subject = "Reset Password";
					$data['user_id'] = $user[0]->user_id;
					$data['date_time'] = date('Y-m-d H:i:s');
					$data['verification_code'] = $code;
					$data['verification_type'] = 'fpass';
					$data['flag'] = false;
					
					if($phone!='' && strlen($phone)==10){
						$mail = $this->MailSystem($value, "", $subject, $msg);
						$sms = $this->SendOTPConfirmation($phone, $code);
						//echo json_encode($mail); exit;
						if($mail || $sms){
							$this->LoginModel->insertData('b2c_verification_code', $data);
							$response = array(
								'status'=>'success',
								'title'=>'OTP Send',
								'email'=>$user[0]->user_id, 
								'code'=>$code,
								'msg'=>'Check your mail or your phone to get OTP.'
							);
						}
					}else{
						$mail = $this->MailSystem($value, "", $subject, $msg);
						$this->LoginModel->insertData('b2c_verification_code', $data);
						$response = array(
							'status'=>'success',
							'title'=>'OTP Send',
							'email'=>$user[0]->user_id, 
							'code'=>$code,
							'msg'=>'Please check your mail and<br>update your mobile for future reference.'
						);
					}
					
				}else{
					$response = array(
						'status'=>'error',
						'title'=>'Invalid',
						'email'=>"", 
						'code'=>"",
						'msg'=>'Email not registered. Invalid User!'
					);
				}
			}else{
				$response = array(
					'status'=>'error',
					'title'=>'Invalid',
					'email'=>"", 
					'code'=>"",
					'msg'=>'Invalid Email!'
				);
			}
		}else{
			$response = array(
				'status'=>'error',
				'title'=>'Missing',
				'email'=>"", 
				'code'=>"",
				'msg'=>'Email value must be entered!'
			);
		}
		echo json_encode($response);
	}
	public function SendOTPConfirmation($phone, $code)
	{
		$authKey = "220681Ap3YnI61Qsx5b237012";
		$senderId = "SVIST";
		$url="http://api.msg91.com/api/sendhttp.php";
		$route = 4; //"default";
		
		$message = urlencode('Your OTP is '.$code);
		$postData = array(
			'authkey' => $authKey,
			'mobiles' => $phone,
			'message' => $message,
			'sender' => $senderId,
			'route' => $route
		);
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		));


		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


		//get response
		$output = curl_exec($ch);

		//Print error if any
		/*if(curl_errno($ch))
		{
			//echo json_encode(array('error'=>curl_error($ch)));
			$response = array(
				'status'=>'error',
				'title'=>'SMS Error',
				'msg'=>'SMS cannot be send!'
			);
		}*/
		$flag = (curl_errno($ch))? false : true;

		curl_close($ch);
		
		return $flag;
	}
	
	public function MailSystem($to, $cc, $subject, $message)
	{
		$email = "admin@billionskills.com";
        $password = "Magic#123";

		$mail = new PHPMailer(true);
		//Enable SMTP debugging.
		$mail->SMTPDebug = 0;                               
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();            
		//Set SMTP host name  
		$mail->CharSet = 'utf-8';// set charset to utf8
		//$mail->Encoding = 'base64';
		$mail->SMTPAuth = true;// Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted

		$mail->Host = 'lotus.arvixe.com';// Specify main and backup SMTP servers
		$mail->Port = 465;// TCP port to connect to
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);		                     
		//Provide username and password     
		$mail->Username = $email;                 
		$mail->Password = $password;                                                                         

		$mail->From = $email;
		$mail->FromName = 'SVIST.org';

		$mail->addAddress($to);

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";

		return ($mail->send())? 1 : $mail->ErrorInfo;
	}
}