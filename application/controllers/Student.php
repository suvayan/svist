<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';


class Student extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member');
		$this->load->model('Course_model');
		$this->load->model('LoginModel');
		$this->load->library('form_validation');
		$this->load->helper('common_helper');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
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
		$mail->FromName = 'Magnox+ Plus';

		$mail->addAddress($to);

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";

		return ($mail->send())? 1 : $mail->ErrorInfo;
	}
	/******************************************************************************/
	public function index()
	{
		if($this->isLoggedIn()){
			$data['userid'] = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Dashboard | SVIST - Student';
			//$data['programs'] = $this->Member->getAllMyPrograms('Student', $data['userid']);
			$data['programs'] = $this->Member->getAllStudPrograms($data['userid']);
			$i=1;
			foreach($data['programs'] as $row)
			{
				$pid = $row->id;
				$spc_id = $row->spc_id;
				$data['pcourses'.$i] = $this->Member->getStudProgCons($data['userid'], $pid, $spc_id);
				$i++;
			}
			$this->loadUserViews("index", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	/*public function findUserApply()
	{
		$stdApply = $this->Member->getUserApplication($_SESSION['userData']['email']);
		
		echo json_encode($stdApply);
	}*/
	public function findUserApply()
	{
		$resp = array('status'=>false, 'prog_id'=>'', 'prog_name'=>'', 'apply_status'=>false);
		//$stdApply = $this->Member->getUserApplication($_SESSION['userData']['email']);
		$userProg = json_decode(get_cookie('userProgram'));
		if(isset($userProg)){
			$userid = $userProg->userId;
			$progid = $userProg->progId;
			$progname = $userProg->progName;
			if(!$userProg->status){
				$chk_adm = $this->Member->checkReduntAdmission($progid, $userid);
				if($userid==$_SESSION['userData']['userId']){
					if($chk_adm==0){
						$resp = array('status'=>true, 'prog_id'=>$progid, 'prog_name'=>$progname, 'apply_status'=>false);
					}else{
						$resp = array('status'=>true, 'prog_id'=>$progid, 'prog_name'=>$progname, 'apply_status'=>true);
					}
				}
			}else{
				$resp = array('status'=>true, 'prog_id'=>$progid, 'prog_name'=>$progname, 'apply_status'=>true);
			}
			if (isset($_COOKIE['userProgram'])) {
				unset($_COOKIE['userProgram']);
				setcookie('userProgram', '', time() - 3600, '/'); // empty value and old timestamp
			}
		}else{
			$resp = array('status'=>false, 'prog_id'=>'', 'prog_name'=>'', 'apply_status'=>false);
		}
		
		echo json_encode($resp);
	}
	public function updateApplications()
	{
		$str=[];
		$resp = array('title'=>'Failed', 'status'=>'error', 'msg'=>'Something went wrong.');
		if(isset($_POST['stdApply'])){
			$chkUser = $_POST['stdApply'];
			$stdApply = $this->Member->getUserApplication($_SESSION['userData']['email']); 
			$ccu = count($chkUser);
			$csa = count($stdApply);
			if(($csa-$ccu)==0){
				$data['status'] = 'accepted';
				$where = "user_email='".$_SESSION['userData']['email']."' AND status='pending'";
				$this->LoginModel->updateData('pro_user_invite', $data, $where);
				for($i=0; $i<$ccu; $i++){
					$str[$i] = explode("_",$chkUser[$i]);
					$data[$i]['program_id'] = $str[$i][1];
					$data[$i]['user_id'] = $_SESSION['userData']['userId'];
					$data[$i]['role'] = 'Student';
					$data[$i]['add_date'] = date('Y-m-d H:i:s');
					$data[$i]['status'] = 'pending';
					$this->LoginModel->insertData('pro_users_role', $data[$i]);
				}
				$resp = array('title'=>'All programs', 'status'=>'success', 'msg'=>'have been applied. Check on "View request for approval."');
			}else{
				for($i=0; $i<$ccu; $i++){
					$str[$i] = explode("_",$chkUser[$i]);
					$data[$i]['program_id'] = $str[$i][1];
					$data[$i]['user_id'] = $_SESSION['userData']['userId'];
					$data[$i]['role'] = 'Student';
					$data[$i]['add_date'] = date('Y-m-d H:i:s');
					$data[$i]['status'] = 'pending';
					$this->LoginModel->insertData('pro_users_role', $data[$i]);
					
					$where1 = 'sl='.$str[$i][0];
					$data2[$i]['status'] = 'accepted';
					$this->LoginModel->updateData('pro_user_invite', $data2[$i], $where1);
				}
				$where = "user_email='".$_SESSION['userData']['email']."' AND status='pending'";
				$this->LoginModel->deleteData('pro_user_invite', $where);
				$resp = array('title'=>$ccu.' programs', 'status'=>'success', 'msg'=>'have been applied and '.($csa-$ccu).' were removed. Check on "View request for approval."');
			}
		}else{
			$resp = array('status'=>'success', 'msg'=>'application/s removed.');
		}
		echo json_encode($resp);
	}
	
	public function updateUserPassword()
	{
		$resp = array('status' => false);
		$user_id = $_SESSION['userData']['userId'];
		$password = md5(trim($_POST['pass']));
		$data2['password'] = $password;
		$where2 = 'user_id='.$user_id;
		
		if($this->Member->updateData('user_auth', $data2, $where2)){
			$resp['status'] = true;
		}
		echo json_encode($resp);
	}
	public function userProfile()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Profile | SVIST - Student';
			$data['udetails'] = $this->Member->getUserDetailsById($userid);
			$data['degree'] = $this->LoginModel->getAllDegrees();
			$data['skills'] = $this->LoginModel->getAllSkills();
			$data['uskills'] = $this->Member->getAllUserSkillsArray($userid);
			$data['uacademic'] = $this->Member->getAllUserAcademic($userid);
			$this->loadUserViews("profile", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function updateProfile()
	{
		$id = $_SESSION['userData']['userId'];
		$ori = $_FILES['avatar']['name'];
		$tmp = $_FILES['avatar']['tmp_name'];
		$thmb = uniqid().$ori;
		$ori1 = $_FILES['resume']['name'];
		$tmp1 = $_FILES['resume']['tmp_name'];
		$thmb1 = 'Magnox'.date('dmyhi').$ori1;
		
		$data_img = $_POST['crop_img']; 
		if($data_img!=""){
			list($type, $data_img) = explode(';', $data_img);
			list(, $data_img)      = explode(',', $data_img);	
		}
		
		$data['first_name'] = ucfirst(strtolower(trim($_POST['firstname'])));
		$data['last_name'] = ucfirst(strtolower(trim($_POST['lastname'])));
		if($ori!=''){
			$data['photo_sm'] = 'assets/img/users/'.$thmb;
			//$data['photo_lg'] = 'public/image/profile_pic/lg/'.$thmb;
		}
		$data['email'] = strtolower(trim($_POST['email']));
		$data['phone'] = trim($_POST['phone']);
		$data['modified_date_time'] = date('Y-m-d H:i:s');
		
		$this->Member->deleteUserSkills($id);
		$this->Member->deleteUserAcademic($id);
		
		$ecount = trim($_POST['educount']);
		$uskills = $_POST['skills'];
		$cskill = count($uskills);
		
		if($this->Member->updateUserAuth($data, $id)){
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/users/'.$thmb, base64_decode($data_img));
					//file_put_contents('./public/image/profile_pic/lg/'.$thmb, base64_decode($data_img));
				}
			}
			if($ori1!=''){
				move_uploaded_file($tmp1,'./uploads/resume/'.$thmb1);
				$data['resume_heading'] = 'uploads/resume/'.$thmb1;
			}
			$sessionArray = $this->session->userdata('userData');
			$this->session->unset_userdata('userData');
			$sessionArray['userId'] = $id;
			$sessionArray['email'] = trim($data['email']);
			$sessionArray['name'] = trim($data['first_name'].' '.$data['last_name']);
			if($ori!=""){
				$sessionArray['photo'] = 'assets/img/users/'.$thmb;
			}
			$sessionArray['isLoggedIn'] = true;
			$this->session->set_userdata('userData',$sessionArray);
			
			$data['dateofbirth'] = date('Y-m-d',strtotime($_POST['dob']));
			$data['gender'] = $_POST['gender'];
			$data['alt_email'] = trim($_POST['alt_email']);
			$data['alt_phone'] = trim($_POST['alt_phone']);
			$data['website'] = trim($_POST['website']);
			$data['address'] = trim($_POST['address']);
			$data['facebook_link'] = trim($_POST['facebook']);
			$data['linkedin_link'] = trim($_POST['linkedin']);
			$data['google_link'] = trim($_POST['google']);
			$this->Member->updateUserDetails($data, $id);
			
			for($i=0; $i<=$ecount; $i++){
				$data2[$i]['user_id'] = $id;
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
			if($cskill>0){
				for($j=0; $j<$cskill; $j++){
					$data3[$j]['user_id'] = $id;
					$data3[$j]['skill_id'] = $uskills[$j];
					$data3[$j]['rank'] = 0;
					$data3[$j]['create_date_time'] = date('Y-m-d H:i:s');
					if($data3[$j]['skill_id']!=""){
						$this->LoginModel->insertUserSkills($data3[$j]);
					}
				}
			}
			
			$this->session->set_flashdata('success', 'Profile details has been updated');
		}else{
			$this->session->set_flashdata('error', 'Profile details updation failed');
		}
		
		redirect(base_url().'Student/userProfile');
	}
	
	public function getUserValidData()
	{
		$userid = $_SESSION['userData']['userId'];
		$data['type'] = trim($_GET['dtype']);
		if($data['type']=='resume'){
			$data['resume'] = $this->Member->getUserDetailsById($userid);
		}else if($data['type']=='skills'){
			$data['skills'] = $this->Member->getAllUserSkillsArray($userid);
		}
		return $this->load->view('users/user_data', $data);
	}
	
	public function programAdmission()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$data['prog_id'] = base64_decode($_GET['id']);
			$checkAdm = $this->Member->checkReduntAdmission($data['prog_id'], $userid);
			if($checkAdm==0){
				$this->global['pageTitle'] = 'Program Admission | SVIST - Student';
				$data['ud'] = $this->Member->getUserDetailsById($userid);
				$data['prog'] = $this->LoginModel->getProgramById($data['prog_id']);
				$data['degree'] = $this->LoginModel->getAllDegrees();
				$data['uacademic'] = $this->Member->getAllUserAcademic($userid);
				$this->loadUserViews("program-admission", $this->global, $data, NULL);
			}else{
				$this->session->set_flashdata('error', 'You have already applied for admission on this program.');
				redirect(base_url().'Student/allPrograms');
			}
		}else{
			redirect(base_url());
		}
	}
	public function userAdmission()
	{
		$userid = $_SESSION['userData']['userId'];
		$ud = $this->Member->getUserDetailsById($userid);
		$ecount = trim($_POST['educount']);
		$pid = trim($_POST['pid']);
		$pdetails = $this->LoginModel->getProgramInfoById($pid);
		$apply_type = (int)$pdetails[0]->apply_type;
		
		$subject = 'SVIST Learning Registration';
		$msg = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="content-type" content="text/html;charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /><style>*{margin:0;padding:0;}body {font-size:14px;font-family: monospace;}table, th, td {border: 1px solid black;border-collapse: collapse;}th {text-align: left;}th, td {padding: 15px;}.container {width: 90%;margin: 0 auto;padding: 3rem 2rem;}</style></head><body><div class="container">Hello '.trim($ud[0]->first_name." ".$ud[0]->last_name).',<br><h4 style="text-center">Thank you for your registration in Magnox Learning +</h4>Your application has been '.(($apply_type==0)? 'approved':'saved').'. You application is as follow:<br><br><table width="100%"><tr><th width="20%">Program</th><td width="0%">'.trim($pdetails[0]->title).'</td></tr><tr><th width="20%">Duration</th><td width="0%">'.trim($pdetails[0]->duration).' '.trim($pdetails[0]->dtype).'(s)</td></tr><tr><th width="20%">Application Start Date</th><td width="0%">'.date('jS M Y',strtotime($pdetails[0]->start_date)).'</td></tr><tr><th width="20%">Program Administrator</th><td width="0%">'.trim($pdetails[0]->uname).'</td></tr><tr><th width="20%">Program details</th><td width="0%">For more details of the program, Please | <a href="'.base_url('programDetails/?id='.base64_encode($pdetails[0]->id)).'" target="_blank">Click Here</a></td></tr></table><br><br>Your Application is half way done. Please have patience for the approval in your dashboard in order to '.(($apply_type==0)? 'access the program':'complete your admission procedure').'.<br><br><br>Thanking you,<br>Magnox Learning Plus,<br>Learning and Course Management Platform</div></body></html>';
		
		if($this->MailSystem(trim($ud[0]->email), '', $subject, $msg)){
			$this->Member->deleteUserAcademic($id);
			for($i=1; $i<=$ecount; $i++){
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
			$this->session->set_flashdata('error', 'Your application has been accepted. Please wait for approval. Check your mail for further details.');
		}else{
			$this->session->set_flashdata('error', 'Mail send error. Server Down. Please try again.');	
		}
		redirect(base_url().'Student/allPrograms');
	}
	
	public function liveClass()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Live Class | SVIST - Student';
			$userid = $_SESSION['userData']['userId'];
			$cid = base64_decode(trim($_GET['id']));
			$data['progcourse'] = $this->Member->getProgramCourse($cid);
			$data['sch_class'] = $this->Course_model->getScheduleClassByCid($cid, $userid);
			$data['pstud'] = $this->Member->getAllRequestByIdRole($data['progcourse'][0]->pid, 'Student', null, 'accepted');
			$data['schClass'] = $this->Course_model->getScheduleClassById($cid);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['progcourse'][0]->pid, 'Teacher', null, 'accepted');
			$data['cid'] = $cid;
			$data['user_id'] = $userid;
			$this->loadUserViews("live-class", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function getNotices()
	{
		$userid = $_SESSION['userData']['userId'];
		$notices = $this->Member->getStudentNotices($userid);
		echo json_encode($notices);
	}
	public function notices()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Notices | SVIST - Student';
			$data['notices'] = $this->Member->getAllNoticesByUid($userid);
			//$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$this->loadUserViews("notice", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function getMenuContent()
	{
		$userid = $_SESSION['userData']['userId'];
		$page = trim($_GET['page']);
		$cid = trim($_GET['cid']);
		$data['cd'] = $this->Course_model->getCourseDetailsById($cid);
		$data['prog_id'] = trim($_GET['prog']);
		
		if($page=='lectures'){
			$data['clectures'] = $this->Course_model->getLecturessByCid($cid);
		}else if($page=='resources'){
			$data['cresource'] = $this->Course_model->getResourcesByCid($cid);
			$i=1;
			foreach($data['cresource'] as $row){
				$id = $row->sl;
				$data['crfiles'.$i] = $this->Course_model->getResourceFilesById($id);
				$i++;
			}
		}else if($page=='assignments'){
			$data['cassignment'] = $this->Course_model->getAssignmentByCid($cid);
			$i=1;
			foreach($data['cassignment'] as $row){
				$id = $row->sl;
				$data['cafiles'.$i] = $this->Course_model->getAssignmentFilesById($id);
				$i++;
			}
		}else if($page=='grade'){
			$data['title'] = 'Grades';
			$data['pcwt'] = $this->Course_model->getOnlySubjectNameByCid($cid);
			$data['setGrade'] = $this->Course_model->getAllAssignmentMarksByCid($cid, $userid);
		}else if($page=='quiz'){
			$data['title'] = 'Quiz';
		}else if($page=='schedule'){
			$data['title'] = 'Schedule Classes';
			$data['schClass'] = $this->Course_model->getScheduleClassById($cid);
		}else if($page=='doubts'){
			$data['title'] = 'Doubts';
			$data['stud_dbs'] = $this->Course_model->getStudentDoubtsById($cid, $userid);
			$data['schClass'] = $this->Course_model->getScheduleClassById($cid);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', null, 'accepted');
		}else if($page=='teachers'){
			$data['title'] = 'Teacher List';
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', null, 'accepted');
		}else if($page=='attendance'){
			$data['title'] = 'Attendance';
		}

		return $this->loadUserCourse('course-'.$page, $data);
	}
	
	public function invitations()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Invitations | SVIST - Student';
			$useremail = $_SESSION['userData']['email'];
			$data['invData'] = $this->Member->getUserInvitationsByEmail($useremail);
			$this->loadUserViews("program-invitation", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/*public function applyInviteToPRogram()
	{
		$resp = array('status'=>false, 'msg'=>'Something went wrong.');
		$prog_id = trim($_POST['pid']);
		$stat = trim($_POST['stat']).'ed';
		$role = $_POST['role'];
		$userid = $_SESSION['userData']['userId'];
		$pdetails = $this->LoginModel->getProgramInfoById($prog_id);
		$ivnd = $this->Member->checkValidInvite($prog_id, $email, $role);
		$cids = $this->Member->getCourseIdsByProgid($prog_id);
		$ftype = trim($pdetails[0]->feetype);
		
		$chkUserRole = $this->Member->checkUserRoleOnProgram($prog_id, $role, $userid);
		$data['status'] = $stat;
		$data['program_id'] = $prog_id;
		$data['respond_datetime'] = date('Y-m-d H:i:s');
		if($this->Member->updateUserInvite($data, $_POST['puid'])){
			if($stat=='accepted'){
				if($chkUserRole<=0){
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
							$resp = array('status'=>true, 'msg'=>'Please pay the amount Rs. '.trim($pdetails[0]->total_fee).' and await for final approval under `My Program Status`.');
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
							$resp = array('status'=>true, 'msg'=>'A new program has been added to your dashboard.');
						}
					}else{
						$resp = array('status'=>true, 'msg'=>'You are have already applied for this program Check `My Program Status`.');
					}
					
				}else{
					$resp = array('status'=>true, 'msg'=>'You are already a Student under this program.');
				}
			}else{
				$resp = array('status'=>true, 'msg'=>'You have successfully rejceted the invitation.');
			}
		}
		echo json_encode($resp);
	}*/
	
	public function addDoubts()
	{
		$resp = array('status'=>'error', 'msg'=>'Something went wrong.');
		$data['program_sl'] = trim($_POST['sch_prog']);
		$data['course_sl'] = trim($_POST['sch_course']);
		$data['stud_sl'] = $_SESSION['userData']['userId'];
		$data['doubts'] = trim($_POST['dbt_details']);
		$data['fac_sl'] = trim($_POST['dbt_prof']);
		$data['schedule_class_sl'] = ($_POST['dbt_schc']!=null)? $_POST['dbt_schc'] : 0;
		$data['status'] = 'pending';
		$data['tdatetime'] = date('Y-m-d H:i:s');
		
		if($data['doubts']!=""){
			if($this->Course_model->insertStudentDoubts($data)){
				$resp = array('status'=>'success', 'msg'=>'has been submitted.');
			}
		}else{
			$resp = array('status'=>'error', 'msg'=>'is empty. Cannot add!');
		}
		
		echo json_encode($resp);
	}
	
	public function message()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Messages | SVIST - Student';
			
			$this->loadUserViews("messages", $this->global, Null, NULL);
		}else{
			redirect(base_url());
		}
	}
	/******************************************************************************/
	public function allPrograms()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'All Programs | SVIST - Student';
			$data['programs'] = $this->Member->getAllProgramsNotUser('Student', $userid);
			$i=1;
			foreach($data['programs'] as $row)
			{
				$pid = $row->id;
				$data['cpprof'.$i] = $this->Member->getTotalProgByUserRole('Teacher', null, $pid);
				$data['cpstud'.$i] = $this->Member->getTotalProgByUserRole('Student', null, $pid);
				$data['cpsub'.$i] = $this->Member->getTotalProgCourses($pid);
				$data['org'.$i] = $this->Member->getProgOrganizations($pid);
				$i++;
			}
			$this->loadUserViews("all-programs", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function requestPrograms()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Status Role for Program | SVIST - Student';
			//$data['rusers'] = $this->Member->getAllRoleStatusByIdRole($userid);
			$data['rusers'] = $this->Member->getUserApplyProgStatus($userid);
			$i=1;
			foreach($data['rusers'] as $row){
				$pid = $row->prog_id;
				$data['spcdata_'.$i] = $this->Member->getUserSPCData($userid, $pid);
				$i++;
			}
			$this->loadUserViews("program-request", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function applyRoleToPRogram()
	{
		$userid = $_SESSION['userData']['userId'];
		$role = trim($_POST['role']);
		$pid = trim($_POST['pid']);
		$chkApply = $this->Member->checkRedundLearningApply($userid, $pid);
		if($chkApply==0){
			$data3['prog_id'] = $pid;
			$data3['cand_id'] = $userid;
			$data3['approve_flag'] = 0;
			$data3['prog_status'] = 0;
			$data3['apply_datetime'] = date('Y-m-d H:i:s');
			
			echo ($this->LoginModel->insertUserAdmission($data3))? '1':'0';
		}else{
			echo '3';
		}
	}
	public function viewProgram()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Programs Details | SVIST - Student';
			$userId = $_SESSION['userData']['userId'];
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['institute'] = $this->Member->getProgramOrg($data['prog_id']);
			$data['stream'] = $this->Member->getProgramStreams($data['prog_id']);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', null, 'accepted');
			$data['procourse'] = $this->Course_model->getProgramCourses($data['prog_id']);;
			$data['cprof'] = $this->Member->getProgRoleRequest('Teacher', $data['prog_id'], $userId);
			$data['cstud'] = $this->Member->getProgRoleRequest('Student', $data['prog_id'], $userId);
			
			$this->loadUserViews("view-program", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function viewProgramDetails()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Programs Details | SVIST - Student';
			$userId = $_SESSION['userData']['userId'];
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['institute'] = $this->Member->getProgramOrg($data['prog_id']);
			$data['stream'] = $this->Member->getProgramStreams($data['prog_id']);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', null, 'accepted');
			$data['procourse'] = $this->Course_model->getProgramCourses($data['prog_id']);;
			//$data['cprof'] = $this->Member->getProgRoleRequest('Teacher', $data['prog_id'], $userId);
			//$data['cstud'] = $this->Member->getProgRoleRequest('Student', $data['prog_id'], $userId);
			
			$this->loadUserViews("view-programDetails", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/*----------------------------------------------------------------------------------*/
	public function courseDetails()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Details | SVIST - Student';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			
			$this->loadUserViews("course-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	/*public function viewLectures()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Lectures | SVIST - Student';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			$data['clectures'] = $this->Course_model->getLecturessByCid($data['cid']);
			
			$this->loadUserViews("course-lectures", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}*/
	public function getLecture()
	{
		$clid = trim($_GET['clid']);
		echo json_encode($this->Course_model->getCLectureByID($clid));
	}
	public function getLectureFile()
	{
		$clid = trim($_GET['clid']);
		$lfile = $this->Course_model->getLectureFile($clid);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['lec_id'] = $clid;
		$data['add_date'] = date('Y-m-d H:i:s');
		if($this->Course_model->insertLecDLrecord($data)){
			echo (file_exists(base_url().'uploads/courselra/'.$lfile[0]->file_name))? $lfile[0]->file_name : 0;
		}else{
			echo 0;
		}
	}
	/*public function viewResources()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Resource | SVIST - Student';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			$data['cresource'] = $this->Course_model->getResourcesByCid($data['cid']);
			$i=1;
			foreach($data['cresource'] as $row){
				$id = $row->sl;
				$data['crfiles'.$i] = $this->Course_model->getResourceFilesById($id);
				$i++;
			}
			
			$this->loadUserViews("course-resource", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}*/
	public function getResource()
	{
		$crid = trim($_GET['crid']);
		echo json_encode($this->Course_model->getCResourceByID($crid));
	}
	public function getResourceYTLink()
	{
		$crid = trim($_GET['crid']);
		echo json_encode($this->Course_model->getCResourceFileByID($crid));
	}
	public function getResourceFiles()
	{
		$crid = trim($_GET['crid']);
		$rfile = $this->Member->getCResourceFileByID($crid);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['res_sl'] = $crid;
		$data['add_date'] = date('Y-m-d H:i:s');
		if($this->Course_model->insertResDLrecord($data)){
			echo json_encode($rfile);
		}else{
			echo 0;
		}
	}
	/*public function viewAssignments()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Assignments | SVIST - Student';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			$data['cassignment'] = $this->Course_model->getAssignmentByCid($data['cid']);
			/*$i=1;
			foreach($data['cassignment'] as $row){
				$id = $row->sl;
				$data['cafiles'.$i] = $this->Course_model->getAssignmentFilesById($id);
				$i++;
			}
			
			$this->loadUserViews("course-assignments", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}*/
	public function getAssignment()
	{
		$caid = trim($_GET['caid']);
		echo json_encode($this->Course_model->getCAssignmentByID($caid));
	}
	public function viewAssignmentDetails()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Assignments | SVIST - Student';
			$aid = base64_decode($_GET['id']);
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			//$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			$data['cadetails'] = $this->Course_model->getCAssignmentByID($aid);
			foreach($data['cadetails'] as $row){
				$id = $row->sl;
				$data['cafiles'] = $this->Course_model->getAssignmentFilesById($id);
			}
			
			$this->loadUserViews("cassignment-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getAssignmentSolutions()
	{
		$id = trim($_GET['id']);
		$userid = $_SESSION['userData']['userId'];
		$data['assgn_sub'] = $this->Course_model->getAssignmentSubmission($id, $userid);
		if(count($data['assgn_sub'])>0){
			$data['asubfiles'] = $this->Course_model->getAssignSubFiles($data['assgn_sub'][0]->sl);
		}
		return $this->load->view("users/assignment-solution", $data);
	}
	public function createAssignmentSubmission()
	{
		$data['ass_sl'] = trim($_POST['ass_sl']);
		$data['user_sl'] = $_SESSION['userData']['userId'];
		$data['status'] = 'Submitted';
		$data['details'] = trim($_POST['rdetails']);
		$data['tdate'] = date('Y-m-d H:i:s');
		if($this->Course_model->insertStudAssignment($data)){
			$resp = array('status'=>'success', 'msg'=>'submission has been created.');
		}else{
			$resp = array('status'=>'error', 'msg'=>'submission could not be created.');
		}
		echo json_encode($resp);
	}
	public function cuCAssignmentFiles()
	{
		$assgn_id = trim($_POST['fassgn_id']);
		$counter = trim($_POST['afcount']);
		
		for($i=0; $i<=$counter; $i++)
		{
			$tmp = $_FILES['fl_link'.$i]['tmp_name'];
			$ori = $_FILES['fl_link'.$i]['name'];
			$rfile = uniqid().$ori;
			if($ori!=''){
				$data[$i]['ass_sub_sl'] = $assgn_id;
				$data[$i]['file_name'] = $rfile;
				$data[$i]['tdate'] = date('Y-m-d H:i:s');
				
				if($this->Course_model->insertCRFiles('pro_ass_sub_files', $data[$i]))
				{
					move_uploaded_file($tmp,'./uploads/stud_assign_sub/'.$rfile);
				}
			}
		}
		echo 1;
	}
	
	/*******************************************************************************/
	
	public function payNow()
	{
		/*
		$data['razorpay_keys'] = array(
			'key_id' => 'rzp_test_eXQfsURE0F1Tf4',
			'key_secret' => 'yLz28baTCVt7QzBfF7lSloNQ'
		);
		*/
		$data['inputs'] = $this->input->post();
		$data['razorpay_keys'] = array(
			'key_id' => 'rzp_live_jYfqswMGR7HXJj',
			'key_secret' => 'w2SilXGmAAK2aKnNMR6HVOqG'
		);
		$this->load->view('razorpay/Razorpay', $data);
	}
	public function paymentSuccess()
	{
		$data['inputs'] = $this->input->post();
		$data['razorpay_keys'] = array(
			'key_id' => 'rzp_live_jYfqswMGR7HXJj',
			'key_secret' => 'w2SilXGmAAK2aKnNMR6HVOqG'
		);
		$this->load->view('razorpay/Razorpay-payment-status', $data);
	}
}