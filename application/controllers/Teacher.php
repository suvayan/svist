<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Teacher extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member');
		$this->load->model('Course_model');
		$this->load->model('Exam_model');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
	}
	/**********************************************************************/
	public function index()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Dashboard | SVIST - Teacher';
			$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$i=1;
			foreach($data['programs'] as $row)
			{
				$pid = $row->id;
				//$data['cpsub'.$i] = $this->Member->getTotalProgCourses($pid);
				$data['pcourses'.$i] = $this->Member->getProfProgramCourses($pid);
				$i++;
			}
			$this->loadTeacherViews("index", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function myProgramRequest()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'My Program Status | SVIST - Teacher';
			$userid = $_SESSION['userData']['userId'];
			$data['myreq'] = $this->Member->getMyRequestForPrograms($userid);
			$this->loadTeacherViews("my-request", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function invitations()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Invitations Status | SVIST - Teacher';
			$useremail = $_SESSION['userData']['email'];
			$userid = $_SESSION['userData']['userId'];
			$data['invData'] = $this->Member->getUserInvitationsByEmail($useremail);
			$data['invResp'] = $this->Member->getAllMyInvitationRespondById($userid);
			$this->loadTeacherViews("program-invitation", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function message()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Messages | SVIST - Teacher';
			
			$this->loadTeacherViews("messages", $this->global, NULL, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function questionBank()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Question Bank | SVIST - Teacher';
			$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$data['qbs'] = $this->Exam_model->getAllUserQuestionBanks($userid);
			$data['ttype'] = $this->Exam_model->getAllTestTypes();
			$this->loadTeacherExam("question-bank", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function test()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Test | SVIST - Teacher';
			$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$this->loadTeacherExam("test", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function testDetails()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$data['tid'] = base64_decode($_GET['id']);
			$this->global['pageTitle'] = 'Test Details | SVIST - Teacher';
			$data['test'] = $this->Exam_model->getTestById($data['tid'], $userid);
			
			$this->loadTeacherExam("test-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function addNotice()
	{
		$resp = array();
		$id = trim($_POST['pn_id']);
		$tmp = $_FILES['fl_notice']['tmp_name'];
		$ori = $_FILES['fl_notice']['name'];
		$files = uniqid().$ori;
		
		$data['program_sl'] = trim($_POST['not_prog']);
		$data['course_sl'] = trim($_POST['not_course']);
		$data['title'] = trim($_POST['nottitle']);
		if($ori!=NULL){
			$data['file_name'] = $files;
		}
		$data['userid'] = $_SESSION['userData']['userId'];
		$data['details'] = trim($_POST['notdetails']);
		//
		if($id==0){
			$data['add_date'] = date('Y-m-d H:i:s');
			if($this->Member->insertTeacherNotice($data)){
				if($ori!=NULL){
					move_uploaded_file($tmp, './uploads/notices/'.$files);
				}
				$resp = array('status'=>'success', 'msg'=>'added successfully.');
			}else{
				$resp = array('status'=>'error', 'msg'=>'added failed.');
			}
		}else{
			$data['modified_date'] = date('Y-m-d H:i:s');
			if($this->Member->updateTeacherNotice($data, $id)){
				if($ori!=NULL){
					move_uploaded_file($tmp, './uploads/notices/'.$files);
				}
				$resp = array('status'=>'success', 'msg'=>'updated successfully.');
			}else{
				$resp = array('status'=>'error', 'msg'=>'added failed.');
			}
		}
		
		echo json_encode($resp);
	}
	public function getNotices()
	{
		$userid = $_SESSION['userData']['userId'];
		$notices = $this->Member->getNoticesBYFid($userid);
		echo json_encode($notices);
	}
	public function getNoticeByID()
	{
		$id = trim($_GET['id']);
		$notices = $this->Member->getNoticesBYid($id);
		echo json_encode($notices);
	}
	public function delNotice()
	{
		$id = trim($_GET['pnid']);
		echo $this->Member->deleteNoticeById($id);
	}
	public function notices()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Notices | SVIST - Teacher';
			$data['notices'] = $this->Member->getAllNoticesByUid($userid);
			$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$this->loadTeacherViews("notice", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	public function addScheduleClass()
	{
		$resp = array();
		$data['program_sl'] = trim($_POST['sch_prog']);
		$data['course_sl'] = trim($_POST['sch_course']);
		$data['class_title'] = trim($_POST['schtitle']);
		$data['class_type'] = (isset($_POST['schLine']))? 'Online' : 'Offline';
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['onlinemedium'] = trim($_POST['sch_online']);
		$data['start_datetime'] = date('Y-m-d H:i:s',strtotime($_POST['sch_start']));
		$data['end_datetime'] = date('Y-m-d H:i:s',strtotime($_POST['sch_end']));
		$data['notify'] = (isset($_POST['schNotify']))? true : false;
		$data['add_datetime'] = date('Y-m-d H:i:s');
		
		if($this->Member->insertScheduleClass($data)){
			$resp = array('status'=>'success', 'msg'=>'has been scheduled.');
		}else{
			$resp = array('status'=>'error', 'msg'=>'could not be scheduled.');
		}
		echo json_encode($resp);
	}
	
	public function checkValidCourse()
	{
		$resp = array('status'=>'error', 'msg'=>'Something went wrong.', 'cid'=>'');
		$ccode = strtoupper(trim($_POST['course_code']));
		
		if($ccode!=""){
			$course = $this->Course_model->getCourseByCode($ccode);
			if(count($course)==1){
				$resp['status']='success';
				$resp['cid']=$course[0]->id;
			}else{
				$resp['msg'] = 'is not found. Please try entering valid one.';
			}
		}else{
			$resp['msg'] = 'cannot be empty.';
		}
		echo json_encode($resp);
	}
	
	public function updateDoubts()
	{
		$resp = array('status'=>'error', 'msg'=>'Something went wrong.');
		$dbtid = $_POST['dbt_id'];
		
		$data['db_ans'] = trim($_POST['dbt_details']);
		$data['status'] = 'done';
		$data['status_ch_datetime'] = date('Y-m-d H:i:s');
		$data['ans_by'] = $_SESSION['userData']['userId'];
		
		if($dbtid!=0 && $data['db_ans']!=''){
			if($this->Course_model->updateStudentDoubts($data, $dbtid)){
				$resp = array('status'=>'success', 'msg'=>'has been answered.');
			}
		}
		
		echo json_encode($resp);
	}
	
	public function requestLearning()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Learning Requests | SVIST - Teacher';
			$data['progs'] = $this->Member->getCurLearningProg($userid);
			$this->loadTeacherViews("learning-request", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getProgLearningList()
	{
		$pid = trim($_GET['pid']);
		$data['prog'] = $this->Member->getProgramById($pid);
		$data['adnpd_list'] = $this->Member->getStudAdmissionListBYAF($pid, '0');
		$data['adnfa_list'] = $this->Member->getStudAdmissionListBYAF($pid, '1');
		$data['sems'] = $this->Course_model->getAllSemestersByProgId($pid);
		$data['ayr'] = $this->Member->getAllActiveAcaYear();
		$i=1;
		foreach($data['adnpd_list'] as $row){
			$uid = $row->cand_id;
			$data['udaca_'.$i] = $this->Member->getUserAcademic($uid);
			$i++;
		}
		return $this->load->view('teachers/stud-learning', $data);
	}
	
	public function manageAdmission()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Manage Admission | SVIST - Teacher';
			$data['progs'] = $this->Member->getCurAdmissionProg($userid);
			$this->loadTeacherViews("manage-admission", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getProgAdmissionList()
	{
		$pid = trim($_GET['pid']);
		$data['prog'] = $this->Member->getProgramById($pid);
		$data['adnpd_list'] = $this->Member->getStudAdmissionListBYAF($pid, '0');
		$data['adnfa_list'] = $this->Member->getStudAdmissionListBYAF($pid, '1');
		$data['sems'] = $this->Course_model->getAllSemestersByProgId($pid);
		$data['ayr'] = $this->Member->getAllActiveAcaYear();
		$i=1;
		foreach($data['adnpd_list'] as $row){
			$uid = $row->cand_id;
			$data['udaca1_'.$i] = $this->Member->getUserAcademic($uid);
			$i++;
		}
		return $this->load->view('teachers/stud-admission', $data);
	}
	public function firstSelectedStud()
	{
		$pid = $_POST['prog_id1'];
		$ftype = $_POST['feetype1'];
		$acp_ids = $_POST['acp1'];
		$cacp = count($acp_ids);
		$ayear = $_POST['acayear'];
		
		$users = $this->Member->getUsersArrayByACPIds($acp_ids);
		$user_ids = $this->Member->getUserIdsByACPIds($acp_ids);
		$prog = $this->Member->getProgramById($pid);
		$subject = "Program Admission Notice";
		$message = 'You have been selected in 1st merit list for the program: '.trim($prog[0]->title).'.<br>';
		if($ftype==1){
			$message.='Please pay the amount within last date of Admission or else your appllication will be rejected.';
		}else{
			$message.='Please wait for final approval.';
		}
		$message.='<br>Log in to your dashboard <a href="'.base_url().'" target="_blank">here</a> for more details.';
		$message.='<hr><br>Thanking You,<br>SVIST';
		
		if($this->MultipleMailSystem($users, $subject, $message)){
			$this->Member->updateMultiAdmApply($acp_ids, 1);
			for($i=0; $i<$cacp; $i++){
				$uid = $user_ids[$i]->id;
				
				$data1[$i]['stud_id'] = $uid;
				$data1[$i]['prog_id'] = $pid;
				$data1[$i]['aca_yearid'] = $ayear;
				$data1[$i]['admission_date'] = date('Y-m-d H:i:s');
				$data1[$i]['status'] = 0;
				$data1[$i]['totalfees'] = (int)trim($prog[0]->total_fee);
				$data1[$i]['discount'] = (int)trim($prog[0]->discount);
				$data1[$i]['add_datetime'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertDataRetId('stud_prog_cons', $data1[$i]);
			}
			echo true;
		}else{
			echo false;
		}
	}
	public function rejectPendingSelectedStud()
	{
		$pid = $_POST['prog_id1'];
		$acp_ids = $_POST['acp1'];
		//$count = count($acp_ids);
		$prog = $this->Member->getProgramById($pid);
		$subject = "Program Rejection Notice";
		$users = $this->Member->getUsersArrayByACPIds($acp_ids);
		$message = 'The admission for the program: '.trim($prog[0]->title).', in which you have applied, has been rejected.<br>Thanking You,<br>SVIST';
		if($this->MultipleMailSystem($users, $subject, $message)){
			$this->Member->deleteMultipleDataById('adm_can_apply', 'sl', $acp_ids);
			echo true;
		}else{
			echo false;
		}
	}
	
	public function finalSelectedStud()
	{
		$data = [];
		$data1 = [];
		$data2 = [];
		$data3 = [];
		$pid = $_POST['prog_id2'];
		$acp_ids = $_POST['acp2'];
		$ftype = $_POST['feetype2'];
		$semid = $_POST['semid'];
		$counter = count($acp_ids);
		
		$prog = $this->Member->getProgramById($pid);
		$users = $this->Member->getUsersArrayByACPIds($acp_ids);
		$user_ids = $this->Member->getUserIdsByACPIds($acp_ids);
		$cids = $this->Member->getCourseIdsByProgid($pid, $semid);
		$subject = "Program Admission Notice";
		$message = 'You have been selected for the program: '.trim($prog[0]->title).'.<br>';
		$message.='Log in to your dashboard <a href="'.base_url().'" target="_blank">here</a> to start your program.';
		$message.='<hr><br>Thanking You,<br>SVIST';
		if(!empty($cids)){
			if($this->MultipleMailSystem($users, $subject, $message)){
				$this->Member->updateMultiAdmApply($acp_ids, 2);
				for($i=0; $i<$counter; $i++){
					$uid = $user_ids[$i]->id;
					
					$data[$i]['user_id'] = $uid;
					$data[$i]['program_id'] = $pid;
					$data[$i]['add_date'] = date('Y-m-d H:i:s');
					$data[$i]['status'] = 'accepted';
					$data[$i]['role'] = 'Student';
					$this->Member->insertUserRole($data[$i]);
					
					$acpid = $acp_ids[$i];
					
					$data1[$i]['enrollment_no'] = trim($_POST['enroll_'.$acpid]);
					$where = 'sl='.$_POST['spcid_'.$acpid];
					$this->Exam_model->updateData('stud_prog_cons', $data1[$i], $where);
					
					$data2[$i]['stud_id'] = $uid;
					$data2[$i]['spc_id'] = trim($_POST['spcid_'.$acpid]);
					$data2[$i]['roll_no'] = trim($_POST['roll_'.$acpid]);
					$data2[$i]['aca_year'] = date('Y');
					$data2[$i]['status'] = true;
					$data2[$i]['add_date'] = date('Y-m-d H:i:s');
					$sps_id = $this->Exam_model->insertDataRetId('stud_prog_state', $data2[$i]);
					if(!empty($cids)){
						$j=1;
						foreach($cids as $crow){
							$data3[$i][$j]['sps_id'] = $sps_id;
							$data3[$i][$j]['course_id'] = $crow->course_id;
							$data3[$i][$j]['add_date'] = date('Y-m-d H:i:s');
							$this->Exam_model->insertData('stud_prog_course', $data3[$i][$j]);
							$j++;
						}
					}
				}
			}else{
				echo '0';
			}
		}else{
			echo '2';
		}
	}
	public function rejectApprovedSelectedStud()
	{
		$pid = $_POST['prog_id2'];
		$acp_ids = $_POST['acp2'];
		$counter = count($acp_ids);
		$prog = $this->Member->getProgramById($pid);
		$subject = "Program Rejection Notice";
		$users = $this->Member->getUsersArrayByACPIds($acp_ids);
		$message = 'The admission for the program: '.trim($prog[0]->title).', in which you have applied, has been rejected.<br>Thanking You,<br>SVIST';
		if($this->MultipleMailSystem($users, $subject, $message)){
			$this->Member->deleteMultipleDataById('adm_can_apply', 'sl', $acp_ids);
			for($i=0; $i<$counter; $i++){
				$acpid = $acp_ids[$i];
				$where = 'sl='.$_POST['spcid_'.$acpid];
					$this->Exam_model->deleteData('stud_prog_cons', $where);
			}
			echo true;
		}else{
			echo false;
		}
	}
	/*******************************INVITATION*****************************/
	public function getUserList()
	{
		$utype = trim($_GET['type']);
		$prog_id = trim($_GET['prog']);
		
		$userList = $this->Member->getUserListNotInProgram($prog_id, $utype);
		echo json_encode($userList);
	}
	public function inviteENEUser()
	{
		$resp = array('status'=>'error', 'msg'=>'Failed. Something went wrong.');
		
		$exist = trim($_POST['inv_exist']);
		$data['program_id'] = trim($_POST['inv_prog']);
		$data['user_email'] = strtolower(trim($_POST['inv_email']));
		$data['fullname'] = ucwords(strtolower(trim($_POST['inv_fname']." ".$_POST['inv_lname'])));
		$data['phone'] = trim($_POST['inv_phone']);
		$data['role'] = trim($_POST['inv_role']);
		$data['sem_id'] = trim($_POST['semid']);
		$data['acayear_id'] = trim($_POST['ay_id']);
		$data['enrollment_no'] = trim($_POST['enroll']);
		$data['roll_no'] = trim($_POST['roll']);
		$data['status'] = 'pending';
		$data['itype'] = 'invited';
		$data['invite_by'] = $_SESSION['userData']['userId'];
		$data['invite_datetime'] = date('Y-m-d H:i:s');
		
		$chkInvite = $this->Member->checkValidInvite($data['program_id'], $data['user_email'], $data['role']);
		if(empty($chkInvite)){
			$pdetails = $this->LoginModel->getProgramInfoById($data['program_id']);
			$user = $this->LoginModel->checkVerifyUser($data['user_email']);
			$uri = base_url().'Login/inviteRespondStatus/?email='.base64_encode($data['user_email']).'&prog='.base64_encode($data['program_id']).'&role='.$data['role'];
			$subject = 'Program Invitation';
			$message = '<!DOCTYPE html><html lang="en"><head><meta http-equiv="content-type" content="text/html;charset=utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" /><style>*{margin:0;padding:0;}body {font-size:14px;font-family: monospace;}table, th, td {border: 1px solid black;border-collapse: collapse;}th {text-align: left;}th, td {padding: 15px;}.container {width: 90%;margin: 0 auto;padding: 3rem 2rem;}</style></head><body><div class="container">Hello '.$data['fullname'].',<br>You have been invited by '.$_SESSION['userData']['name'].' as a role of a '.strtolower($data['role']).' for the program below:<br><br><img src="https://learn.techmagnox.com/assets/img/logo.png" style="width:150px;"/><br><table width="100%"><tr><th width="20%">Program</th><td width="80%">'.trim($pdetails[0]->title).'</td></tr><tr><th width="20%">Duration</th><td width="80%">'.trim($pdetails[0]->duration).' '.trim($pdetails[0]->dtype).'(s)</td></tr><tr><th width="20%">Application Start Date</th><td width="80%">'.date('jS M Y',strtotime($pdetails[0]->start_date)).'</td></tr><tr><th width="20%">Program Administrator</th><td width="80%">'.trim($pdetails[0]->uname).'</td></tr><tr><th width="20%">Program Fees</th><td width="80%">'.((trim($pdetails[0]->feetype=='Paid')? 'Rs '.trim($pdetails[0]->total_fee) : 'Free')).'</td></tr><tr><th width="20%">Program details</th><td width="80%">For more details of the program, Please | <a href="'.base_url('programDetails/?id='.base64_encode($pdetails[0]->id)).'" target="_blank">Click Here</a></td></tr></table><br>Please respond to your invitation : <a href="'.$uri.'&status=accepted" target="_blank">Accept</a> / <a href="'.$uri.'&status=rejected" target="_blank">Reject</a>';
			if(!empty($user)){
				$message.= ', OR login to your account <a href="'.base_url().'" target="_blank">here</a> and check you invitation.';
			}else{
				$message.= ' and receive you account credentials, OR register <a href="'.base_url('register/'.strtolower($data['role'])).'" target="_blank">here</a> and check you invitation after successfull login.';
			}
			$message.= '<br><br><br>Thanking you,<br>Magnox Learning Plus,<br>Learning and Course Management Platform</div></body></html>';
			if($this->MailSystem($data['user_email'], "", $subject, $message)){
				if($this->Member->insertUserInvite($data)){
					$resp = array('status'=>'success', 'msg'=>'was send successfully.');
				}else{
					$resp = array('status'=>'error', 'msg'=>'send error.');
				}
			}else{
				$resp = array('status'=>'error', 'msg'=>'Failed. Something went wrong. Please try again.');
			}
		}else{
			$resp = array('status'=>'error', 'msg'=>'was already send to this user.');
		}
		
		echo json_encode($resp);
	}
	public function applyInviteToPRogram()
	{
		$userid = $_SESSION['userData']['userId'];
		
		$chkUserRole = $this->Member->checkUserRoleOnProgram($_POST['pid'], $_POST['role'], $userid);
		$data['status'] = trim($_POST['stat']).'ed';
		$data['program_id'] = trim($_POST['pid']);
		$data['respond_datetime'] = date('Y-m-d H:i:s');
		if($this->Member->updateUserInvite($data, $_POST['puid'])){
			if($chkUserRole<=0){
				$data1['program_id'] = trim($_POST['pid']);
				$data1['role'] = trim($_POST['role']);
				$data1['add_date'] = $data1['status_ch_date'] = date('Y-m-d H:i:s');
				$data1['user_id'] = $userid;
				$data1['status'] = 'accepted';
				$this->Member->insertUserRole($data1);
			}
			echo true;
		}else{
			echo false;
		}
	}
	/*******************************PROGRAM********************************/
	public function addProgram()
	{
		$data['streams'] = $this->Member->getAllUserDeptsByArray($_SESSION['userData']['streams']);
		$obj = array('id'=>"0",'code'=>"",'title'=>"",'type'=>"",'category'=>"",'duration'=>"",'start_date'=>"",'end_date'=>"",'user_id'=>"",'status'=>"",'total_fee'=>"",'fee_details'=>"",'total_credit'=>"",'overview'=>"",'email'=>"",'mobile'=>"",'facebook'=>"",'linkedin'=>"",'twitter'=>"",'student_enroll'=>"",'teacher_enroll'=>"", 'feetype'=>"", 'dtype'=>"", 'stream_id'=>0);
		$obj1 = array('apply_type'=>"", 'astart_date'=>"", 'aend_date'=>"", 'criteria'=>"", 'ptype'=>"", 'placement'=>"", 'apply_status'=>"", 'sem_type'=>"", 'discount'=>0, 'prog_hrs'=>0, 'total_seat'=>0, 'aca_year'=>0);
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			if(isset($_GET['id'])){
				$id = base64_decode(trim($_GET['id']));
				$this->global['pageTitle'] = 'Update Program | SVIST - Teacher';
				$data['prog'] = $this->Member->getProgramById($id);
				$admn = $this->Member->getAdmissionInfoByPid($id);
				if(!empty($admn)){
					$data['adm'] = $admn;
				}else{
					$data['adm'][0] = (object)$obj1;
				}
			}else{
				$this->global['pageTitle'] = 'Add Program | SVIST - Teacher';
				$data['prog'][0] = (object)$obj;
				$data['adm'][0] = (object)$obj1;
			}
			$data['ayr'] = $this->Member->getAllActiveAcaYear();
			$this->loadTeacherViews("add-program", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function cuProgram()
	{
		$sc = '';
		$pid = trim($_POST['pid']);
		$userid = $_SESSION['userData']['userId'];
		$chk_sems = $this->Member->checkAvailableSemester($pid);
		
		$data['code'] = strtoupper(trim($_POST['pcode']));
		$data['title'] = ucwords(strtolower(trim($_POST['title'])));
		$data['type'] = trim($_POST['type']);
		$data['category'] = trim($_POST['category']);
		$dur = (int)(trim($_POST['duration']));
		$data['duration'] = $dur;
		$data['dtype'] = trim($_POST['dtype']);
		$data['start_date'] = date('Y-m-d',strtotime($_POST['sdate']));
		if(isset($_POST['edate'])){
			$data['end_date'] = date('Y-m-d',strtotime($_POST['edate']));
		}
		$data['user_id'] = $userid;
		$data['feetype'] = 'Free';//trim($_POST['feetype']);
		//$data['total_fee']= ($data['feetype']=='Paid')? trim($_POST['fees']) : '';
		if($data['feetype']=='Paid'){
			$data['total_fee'] = trim($_POST['fees']);
			$data2['discount'] = (trim($_POST['rebate'])!="")? trim($_POST['rebate']):0;
		}else{
			$data['total_fee'] = '';
			$data2['discount'] = 0;
		}
		$data['total_credit'] = trim($_POST['credit']);
		$data['overview'] = trim($_POST['overview']);
		$data['curriculam'] = "";
		$data['selection_procedure'] = "";
		$data['email'] = strtolower(trim($_POST['email']));
		$data['mobile'] = trim($_POST['phone']);
		/*$data['facebook'] = trim($_POST['facebook']);
		$data['twitter'] = trim($_POST['twitter']);
		$data['linkedin'] = trim($_POST['linkedin']);*/
		$data['student_enroll'] = 1;
		$data['teacher_enroll'] = 1;
		
		$data2['apply_type'] = trim($_POST['apply_type']);
		$data2['criteria'] = trim($_POST['criteria']);
		if($chk_sems==0){
			if($data['dtype']=='year'){
				$stype = (int)(trim($_POST['semtype']));
				$quote = ($dur*12)/$stype;
			}else{
				$stype = 0;
				$quote = 1;
			}
			$data2['sem_type'] = $stype;
		}
		$data2['prog_hrs'] = trim($_POST['total_hrs']);
		$data2['total_seat'] = trim($_POST['total_seat']);
		$data2['aca_year'] = trim($_POST['aca_year']);
		$data2['astart_date'] = date('Y-m-d',strtotime($_POST['adstart']));
		$data2['aend_date'] = date('Y-m-d',strtotime($_POST['adend']));
		if($data2['apply_type']=='1'){
			if(isset($_POST['screen_type'])){
				$st = $_POST['screen_type'];
				for($i=0; $i<count($st); $i++){
					$sc.=$st[$i].',';
				}
				$data2['screen_type'] = rtrim($sc, ',');
			}
		}else{
			$data2['screen_type'] = '';
		}
		$data2['ptype'] = trim($_POST['prog_type']);
		$data2['apply_status'] = true;
		
		//$data2['placement'] = trim($_POST['placement']);
		/*$this->Member->removeSemestersByProgId($pid);
		$where = 'program_id='.$pid;
		$this->Exam_model->deleteData('pro_map_sem', $where);*/
		
		if($pid==0){
			$data['status'] = 'approved';
			$data['date_added'] = date('Y-m-d H:i:s');
			
			$prog = $this->Member->insertProgram($data);
			
			$data1['program_id'] = $prog;
			$data1['user_id'] = $userid;
			$data1['role'] = 'Teacher';
			$data1['status'] = 'accepted';
			$data1['add_date'] = date('Y-m-d H:i:s');
			$this->Member->insertUserRole($data1);
			
			$data2['prog_id'] = $prog;
			$this->Member->insertAdmission($data2);
			if($chk_sems==0){
				for($i=1; $i<=$quote; $i++){
					$data3[$i]['user_id'] = $userid;
					$data3[$i]['title'] = 'Sem '.$i;
					$data3[$i]['duration'] = $stype.' months';
					$data3[$i]['add_date'] = date('Y-m-d H:i:s');
					$sem_id[$i] = $this->Exam_model->insertDataRetId('pro_semister', $data3[$i]);
					
					$data4[$i]['sem_id'] = $sem_id[$i];
					$data4[$i]['program_id'] = $prog;
					$data4[$i]['add_date'] = date('Y-m-d H:i:s');
					$this->Exam_model->insertData('pro_map_sem', $data4[$i]);
				}
			}
			$data5['org_id'] = $_SESSION['userData']['org'];
			$data5['program_id'] = $prog;
			$data5['add_date'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('pro_map_org', $data5);
			$data6['stream_id'] = $_POST['dept_id'];
			$data6['program_id'] = $prog;
			$data6['add_date'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('pro_map_stream', $data6);
			
			redirect(base_url().'Teacher/addProgram');
		}else{
			$data['last_updated'] = date('Y-m-d H:i:s');
			$chk_adm = $this->Member->checkAvailProgAdmission($pid);
			$this->Member->updateProgram($data, $pid);
			if($chk_adm==0){
				$data2['prog_id'] = $pid;
				$this->Member->insertAdmission($data2);
			}else{
				$this->Member->updateAdmission($data2, $pid);
			}
			
			if($chk_sems==0){
				for($i=1; $i<=$quote; $i++){
					$data3[$i]['user_id'] = $userid;
					$data3[$i]['title'] = 'Sem '.$i;
					$data3[$i]['duration'] = $stype.' months';
					$data3[$i]['add_date'] = date('Y-m-d H:i:s');
					$sem_id[$i] = $this->Exam_model->insertDataRetId('pro_semister', $data3[$i]);
					
					$data4[$i]['sem_id'] = $sem_id[$i];
					$data4[$i]['program_id'] = $pid;
					$data4[$i]['add_date'] = date('Y-m-d H:i:s');
					$this->Exam_model->insertData('pro_map_sem', $data4[$i]);
				}
			}
			
			redirect(base_url().'Teacher');
		}
	}
	public function addProgramDetails()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			if(isset($_GET['id'])){
				$id = base64_decode(trim($_GET['id']));
				$this->global['pageTitle'] = 'Update Program Details | SVIST - Teacher';
				$data['prog'] = $this->Member->getProgramById($id);
				$data['adm'] = $this->Member->getAdmissionInfoByPid($id);
			}
			$this->loadTeacherViews("add-program-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function cuProgramDetails()
	{
		$pid = trim($_POST['pid']);
		$userid = $_SESSION['userData']['userId'];
		
		$ori = $_FILES['avatar']['name'];
		$tmp = $_FILES['avatar']['tmp_name'];
		$thmb = uniqid().$ori;
		
		$data_img = $_POST['crop_img']; 
		if($data_img!=""){
			list($type, $data_img) = explode(';', $data_img);
			list(, $data_img)      = explode(',', $data_img);	
		}
		
		$tmp1 = $_FILES['schedule']['tmp_name'];
		$src1 = $_FILES['schedule']['name'];
		$file1 = uniqid().$src1;
		
		$tmp2 = $_FILES['brochure']['tmp_name'];
		$src2 = $_FILES['brochure']['name'];
		$file2 = uniqid().$src2;
		if($ori!=""){
			$data['banner'] = $thmb;
		}
		if($src1!=''){
			$data['program_brochure'] = $file1;
		}
		if($src2!=''){
			$data['certificate_sample'] = $file2;
		}
		$data['intro_video_link'] = trim($_POST['intro']);
		$data['overview'] = trim($_POST['overview']);
		$data['requirements'] = trim($_POST['requirement']);
		$data['fee_details'] = trim($_POST['fdetails']);
		$data['why_learn'] = trim($_POST['wlearn']);
		$data['contact_info'] = trim($_POST['contact_info']);
		$data['last_updated'] = date('Y-m-d H:i:s');
		
		$data2['placement'] = trim($_POST['placement']);
			
		if($this->Member->updateProgram($data, $pid))
		{
			$this->Member->updateAdmission($data2, $pid);
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/banner/'.$thmb, base64_decode($data_img));
				}
			}
			if($src1!=''){
				move_uploaded_file($tmp1, './uploads/programs/'.$file1);
			}
			if($src2!=''){
				move_uploaded_file($tmp2, './uploads/programs/'.$file2);
			}
		}
		
		redirect(base_url().'Teacher');
	}
	public function viewProgram()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Programs Details | SVIST - Teacher';
			$userId = $_SESSION['userData']['userId'];
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['institute'] = $this->Member->getProgramOrg($data['prog_id']);
			$data['stream'] = $this->Member->getProgramStreams($data['prog_id']);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', NULL, 'accepted');
			//$data['pstud'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Student', NULL, 'accepted');
			$data['procourse'] = $this->Member->getProfProgramCourses($data['prog_id']);
			$data['cprof'] = $this->Member->getProgRoleRequest('Teacher', $data['prog_id'], $userId);
			//$data['cstud'] = $this->Member->getProgRoleRequest('Student', $data['prog_id'], $userId);
			
			$this->loadTeacherViews("view-program", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	
	/*******************************COURSES********************************/
	public function getCoursebyProg()
	{
		$sl = trim($_GET['sl']);
		$procourse = $this->Course_model->getProgramCourses($sl);
		echo json_encode($procourse);
	}
	public function progCourses()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Programs Courses | SVIST - Teacher';
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['procourse'] = $this->Course_model->getProgramCourses($data['prog_id']);
			
			$this->loadTeacherViews("prog-courses", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function addCourse()
	{
		$prog_id = base64_decode($_GET['prog']);
		$obj = array('id'=>0, 'prog_id'=>$prog_id, 'title'=>"", 'start_date'=>"", 'end_date'=>"", 'total_credit'=>"", 'syllabus'=>"", 'overview'=>"", 'importance'=>"", 'lec'=>"", 'tut'=>"", 'prac'=>"", 'type'=>"", 'c_code'=>"");
		$data['sems'] = $this->Course_model->getAllSemestersByProgId($prog_id);
		if(isset($_GET['cid'])){
			$this->global['pageTitle'] = 'Update Course | Programs';
			$cid = base64_decode($_GET['cid']);
			$data['cd'] = $this->Course_model->getCourseDetailsById($cid);
		}else{
			$this->global['pageTitle'] = 'Add Course | Programs';
			$data['cd'][0] = (object)$obj;
		}
        
        $this->loadTeacherViews("add-course", $this->global, $data , NULL);
	}
	public function cuProCourse()
	{
		$userid = $_SESSION['userData']['userId'];
		$resp = array('status'=>'danger', 'msg'=>'Something went wrong.', 'utype'=>$_SESSION['userData']['utype']);
		$id = trim($_POST['cid']);
		$pid = trim($_POST['prog_id']);
		
		$tmp = $_FILES['syllabus']['tmp_name'];
		$ori = $_FILES['syllabus']['name'];
		$files = uniqid().$ori;
		
		$data['c_code'] = strtoupper(trim($_POST['ccode']));
		$data['title'] = ucwords(strtolower(trim($_POST['title'])));
		$data['start_date'] = date('Y-m-d H:i:s', strtotime($_POST['sdate']));
		$data['end_date'] = date('Y-m-d H:i:s', strtotime($_POST['edate']));
		$data['total_credit'] = trim($_POST['ccredit']);
		$data['overview'] = trim($_POST['overview']);
		$data['importance'] = trim($_POST['importance']);
		if($ori!=''){
			$data['syllabus'] = $files;
		}
		$data['lec'] = trim($_POST['lecture']);
		$data['tut'] = trim($_POST['tutorial']);
		$data['prac'] = trim($_POST['practical']);
		$data['type'] = trim($_POST['crtype']);
		$data['prog_id'] = $pid;
		
		if($id==0){
			$data['post_date'] = date('Y-m-d H:i:s');
			$cid = $this->Course_model->insertProCourse($data);
			if($cid){
				if($ori!=''){
					move_uploaded_file($tmp,'./uploads/courses/'.$files);
				}
				$data1['course_id'] = $cid;
				$data1['program_id'] = $pid;
				$data1['user_id'] = $userid;
				$data1['sem_id'] = $_POST['semid'];
				$data1['add_date'] = date('Y-m-d H:i:s');
				$this->Course_model->insertProMapCource($data1);
				$resp = array('status'=>'success', 'msg'=>'Course added successfully.', 'utype'=>$_SESSION['userData']['utype']);
			}else{
				$resp = array('status'=>'danger', 'msg'=>'Course added error. Something went wrong.', 'utype'=>$_SESSION['userData']['utype']);
			}
		}else{
			if($this->Course_model->updateProCourse($data, $id)){
				if($ori!=''){
					move_uploaded_file($tmp,'./uploads/courses/'.$files);
				}
				$chkpmc = $this->Course_model->checkAvailPMC($pid, $id);
				$data1['course_id'] = $id;
				$data1['program_id'] = $pid;
				$data1['user_id'] = $userid;
				$data1['sem_id'] = $_POST['semid'];
				if($chkpmc==0){
					$data1['add_date'] = date('Y-m-d H:i:s');
					$this->Course_model->insertProMapCource($data1);
				}else{
					$this->Course_model->updateProMapCource($data1, $id, $pid);
				}
				
				$resp = array('status'=>'success', 'msg'=>'Course updated successfully.', 'utype'=>$_SESSION['userData']['utype']);
			}else{
				$resp = array('status'=>'danger', 'msg'=>'Course update error. Something went wrong.', 'utype'=>$_SESSION['userData']['utype']);
			}
		}
		echo json_encode($resp);
	}
	public function courseDetails()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Details | SVIST - Teacher';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			
			$this->loadTeacherViews("course-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/*----------------------------------------------------------------------------------*/
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
	public function cuCLecture()
	{
		$respond = array();
		
		$tmp = $_FILES['lfiles']['tmp_name'];
		$ori = $_FILES['lfiles']['name'];
		$lfile = uniqid().$ori;
		
		$data['course_sl'] = trim($_POST['cid']);
		$prog = trim($_POST['prog']);
		$lecid = trim($_POST['lec_id']);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['title'] = trim($_POST['ltitle']);
		$data['file_type'] = trim($_POST['ltype']);
		if($data['file_type']=='fl'){
			if($ori!=''){
				$data['file_name'] = $lfile;
			}
		}else{
			$data['link'] = trim($_POST['llink']);
		}
		$data['notify'] = (isset($_POST['lnotify']))? true:false;
		$data['archive'] = false;
		$data['lec_date'] = ($_POST['ldate']!='')? date('Y-m-d H:i:s',strtotime($_POST['ldate'])) : date('Y-m-d H:i:s');
		if($data['notify']){
			$users = $this->Member->getStudentNameEmailByProgId($prog);
			$pc = $this->Member->getProgramCourseNameByProgId($prog, $data['course_sl']);
			$subject = 'New Lecture added';
			$message = 'New Lecture has been add to your course: "'.$pc[0]->course_title.'" of program: "'.$pc[0]->program_title.'", by '.$_SESSION['userData']['name'].'.';
			
			$this->MultipleMailSystem($users, $subject, $message);
		}
		if($lecid==0){
			$data['add_date'] = date('Y-m-d H:i:s');
			if($this->Course_model->insertClecture($data)){
				if($ori!='' && $data['file_type']=='fl'){
					move_uploaded_file($tmp,'./uploads/courselra/'.$lfile);
				}
				$respond = array('status'=>'success', 'msg'=>'added successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'addition error');
			}
		}else{
			$data['modified_date'] = date('Y-m-d H:i:s');
			if($this->Course_model->updateClecture($data, $lecid)){
				if($ori!='' && $data['file_type']=='fl'){
					move_uploaded_file($tmp,'./uploads/courselra/'.$lfile);
				}
				$respond = array('status'=>'success', 'msg'=>'updated successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'updation error');
			}
		}
		echo json_encode($respond);
	}
	public function deleteLectures()
	{
		$clid = trim($_GET['clid']);
		$data['archive'] = true;
		/*$lfile = $this->Course_model->getLectureFile($clid);
		if(file_exists(base_url().'uploads/courselra/'.$lfile[0]->file_name)){
			unlink(base_url().'uploads/courselra/'.$lfile[0]->file_name);
		}*/
		//echo ($this->Course_model->delLectureById($clid))? true : false;
		echo ($this->Course_model->updateClecture($data, $clid))? true : false;
	}
	/*----------------------------------------------------------------------------------*/
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
		$rfile = $this->Course_model->getCResourceFileByID($crid);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['res_sl'] = $crid;
		$data['add_date'] = date('Y-m-d H:i:s');
		if($this->Course_model->insertResDLrecord($data)){
			echo json_encode($rfile);
		}else{
			echo 0;
		}
	}
	public function cuCResource()
	{
		$respond = array();
		
		$data['course_sl'] = trim($_POST['cid']);
		$prog = trim($_POST['prog']);
		$resid = trim($_POST['res_id']);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['title'] = trim($_POST['rtitle']);
		$data['desc'] = trim($_POST['rdetails']);
		$data['notify'] = (isset($_POST['rnotify']))? true:false;
		$data['archive'] = false;
		if($data['notify']){
			$users = $this->Member->getStudentNameEmailByProgId($prog);
			$pc = $this->Member->getProgramCourseNameByProgId($prog, $data['course_sl']);
			$subject = 'New Resource added';
			$message = 'New Resource has been add to your course: "'.$pc[0]->course_title.'" of program: "'.$pc[0]->program_title.'", by '.$_SESSION['userData']['name'].'.';
			
			$this->MultipleMailSystem($users, $subject, $message);
		}
		if($resid==0){
			$data['add_date'] = date('Y-m-d H:i:s');
			if($this->Course_model->insertCresource($data)){
				$respond = array('status'=>'success', 'msg'=>'added successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'addition error');
			}
		}else{
			$data['modified_date'] = date('Y-m-d H:i:s');
			if($this->Course_model->updateCresource($data, $resid)){
				$respond = array('status'=>'success', 'msg'=>'added successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'addition error');
			}
		}
		echo json_encode($respond);
	}
	public function cuCResourceFiles()
	{
		$cid = trim($_POST['fcid']);
		$prog = trim($_POST['fprog']);
		$resid = trim($_POST['fres_id']);
		$counter = trim($_POST['rfcount']);
		
		for($i=0; $i<=$counter; $i++)
		{
			if(isset($_POST['yt_link'.$i]))
			{
				$data[$i]['res_sl'] = $resid;
				$data[$i]['type'] = trim($_POST['youtube'.$i]);
				$data[$i]['linkfile'] = trim($_POST['yt_link'.$i]);
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				
				$this->Course_model->insertCRFiles('pro_res_fileslinks', $data[$i]);
			}
			if(isset($_POST['lk_link'.$i]))
			{
				$data[$i]['res_sl'] = $resid;
				$data[$i]['type'] = trim($_POST['link'.$i]);
				$data[$i]['linkfile'] = trim($_POST['lk_link'.$i]);
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				
				$this->Course_model->insertCRFiles('pro_res_fileslinks', $data[$i]);
			}
			if(isset($_FILES['fl_link'.$i]['name']))
			{
				$tmp = $_FILES['fl_link'.$i]['tmp_name'];
				$ori = $_FILES['fl_link'.$i]['name'];
				$rfile = uniqid().$ori;
				
				$data[$i]['res_sl'] = $resid;
				$data[$i]['type'] = trim($_POST['files'.$i]);
				$data[$i]['file_type'] = pathinfo($ori, PATHINFO_EXTENSION);;
				$data[$i]['linkfile'] = $rfile;
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				
				if($this->Course_model->insertCRFiles('pro_res_fileslinks', $data[$i]))
				{
					move_uploaded_file($tmp,'./uploads/cresources/'.$rfile);
				}
			}
		}
		echo 1;
	}
	public function deleteResources()
	{
		$crid = trim($_GET['crid']);
		$data['archive'] = true;
		/*$rfile = $this->Course_model->getCResourceFileByRID($crid);
		foreach($rfile as $rrow)
		{
			if(file_exists('./uploads/cresources/'.$rrow->linkfile)){
				unlink('./uploads/cresources/'.$rrow->linkfile);
			}
		}
		$this->Course_model->delResourceFilesByRID($crid);*/
		echo ($this->Course_model->updateCresource($data, $crid))? true : false;
	}
	public function deleteResourceFiles()
	{
		$crid = trim($_GET['crid']);
		//$data['archive'] = true;
		$rfile = $this->Course_model->getCResourceFileByRID($crid);
		foreach($rfile as $rrow)
		{
			if(file_exists('./uploads/cresources/'.$rrow->linkfile)){
				unlink('./uploads/cresources/'.$rrow->linkfile);
			}
		}
		echo ($this->Course_model->delResourceFilesByID($crid))? true : false;
	}
	/*----------------------------------------------------------------------------------*/
	public function getAssignment()
	{
		$caid = trim($_GET['caid']);
		echo json_encode($this->Course_model->getCAssignmentByID($caid));
	}
	public function cuCAssignment()
	{
		$respond = array();
		
		$data['course_sl'] = trim($_POST['cid']);
		$prog = trim($_POST['prog']);
		$assgnid = trim($_POST['assgn_id']);
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['title'] = trim($_POST['atitle']);
		$data['details'] = trim($_POST['adetails']);
		//$data['marks'] = trim($_POST['amarks']);
		$data['tdate'] = date('Y-m-d H:i:s',strtotime($_POST['sdate']));
		$data['deadline'] = date('Y-m-d H:i:s',strtotime($_POST['ddate']));
		$data['publish'] = (isset($_POST['aPublish']))? true:false;
		$data['notify'] = (isset($_POST['anotify']))? true:false;
		$data['archive'] = false;
		
		$data1['course_sl'] = trim($_POST['cid']);
		$data1['type'] = 'Assignment';
		$data1['subject'] = trim($_POST['atitle']);
		$data1['user_id'] = $_SESSION['userData']['userId'];
		$data1['weightage'] = '40';
		$data1['full_marks'] = trim($_POST['amarks']);
		
		
		if($data['notify']){
			$users = $this->Member->getStudentNameEmailByProgId($prog);
			$pc = $this->Member->getProgramCourseNameByProgId($prog, $data['course_sl']);
			$subject = 'New Assignment added';
			$message = 'New Assignment has been add to your course: "'.$pc[0]->course_title.'" of program: "'.$pc[0]->program_title.'", by '.$_SESSION['userData']['name'].'.';
			
			$this->MultipleMailSystem($users, $subject, $message);
		}
		if($assgnid==0){
			$data['add_date'] = date('Y-m-d H:i:s');
			$data1['tdatetime'] = date('Y-m-d H:i:s');
			$asid = $this->Course_model->insertCAssignment($data);
			if($asid!=NULL){
				$data1['serial'] = $asid;
				$this->Course_model->insertCWeightage($data1);
				$respond = array('status'=>'success', 'msg'=>'added successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'addition error');
			}
		}else{
			$data['modified_date'] = date('Y-m-d H:i:s');
			$data1['modified_datetime'] = date('Y-m-d H:i:s');
			if($this->Course_model->updateCAssignment($data, $assgnid)){
				$this->Course_model->updateCWeightage($data1, $assgnid);
				$respond = array('status'=>'success', 'msg'=>'updated successfully');
			}else{
				$respond = array('status'=>'error', 'msg'=>'updation error');
			}
		}
		echo json_encode($respond);
	}
	public function cuCAssignmentFiles()
	{
		$cid = trim($_POST['fcid']);
		$prog = trim($_POST['fprog']);
		$assgn_id = trim($_POST['fassgn_id']);
		$counter = trim($_POST['afcount']);
		
		for($i=0; $i<=$counter; $i++)
		{
			if(isset($_POST['lk_link'.$i]))
			{
				$data[$i]['assgn_sl'] = $assgn_id;
				$data[$i]['file_type'] = trim($_POST['link'.$i]);
				$data[$i]['file_name'] = trim($_POST['lk_link'.$i]);
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				
				$this->Course_model->insertCRFiles('pro_assgn_files', $data[$i]);
			}
			if(isset($_FILES['fl_link'.$i]['name']))
			{
				$tmp = $_FILES['fl_link'.$i]['tmp_name'];
				$ori = $_FILES['fl_link'.$i]['name'];
				$rfile = uniqid().$ori;
				
				$data[$i]['assgn_sl'] = $assgn_id;
				$data[$i]['file_type'] = trim($_POST['files'.$i]);
				$data[$i]['file_ext'] = pathinfo($ori, PATHINFO_EXTENSION);;
				$data[$i]['file_name'] = $rfile;
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				
				if($this->Course_model->insertCRFiles('pro_assgn_files', $data[$i]))
				{
					move_uploaded_file($tmp,'./uploads/cassignments/'.$rfile);
				}
			}
		}
		
		echo 1;
	}
	public function deleteAssignments()
	{
		$caid = trim($_GET['caid']);
		$data['archive'] = true;
		/*$afile = $this->Course_model->getAssignmentFilesById($caid);
		foreach($afile as $arow)
		{
			if(file_exists('./uploads/cassignments/'.$arow->file_name)){
				unlink('./uploads/cassignments/'.$arow->file_name);
			}
		}
		$this->Course_model->delAssignmentFilesByAID($caid);*/
		//$this->Course_model->delAssignmentById($caid)
		echo ($this->Course_model->updateCAssignment($data, $caid))? true : false;
	}
	public function deleteAssignmentFiles()
	{
		$caid = trim($_GET['caid']);
		$afile = $this->Course_model->getAssignmentFilesById($caid);
		foreach($afile as $arow)
		{
			if(file_exists('./uploads/cassignments/'.$arow->file_name)){
				unlink('./uploads/cassignments/'.$arow->file_name);
			}
		}
		echo ($this->Course_model->delAssignmentFilesByID($caid))? true : false;
	}
	
	public function viewAssignmentDetails()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Course Assignments | SVIST - Teacher';
			$aid = base64_decode($_GET['id']);
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			//$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			$data['cadetails'] = $this->Course_model->getCAssignmentByID($aid);
			foreach($data['cadetails'] as $row){
				$id = $row->sl;
				$data['cafiles'] = $this->Course_model->getAssignmentFilesById($id);
			}
			
			$this->loadTeacherViews("cassignment-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getAssignmentSolutions()
	{
		$id = trim($_GET['id']);
		//$userid = $_SESSION['userData']['userId'];
		$data['assgn_sub'] = $this->Course_model->getStudentAssignmentSubmission($id);
		if(count($data['assgn_sub'])>0){
			$data['asubfiles'] = $this->Course_model->getAssignSubFiles($data['assgn_sub'][0]->sl);
		}
		return $this->load->view("teachers/assignment-solution", $data);
	}
	public function updateStudentMarks()
	{
		$data['stud_sl'] = trim($_POST['stu_sl']);
		$data['pro_course_wt_sl'] = trim($_POST['pc_wt']);
		$data['marks'] = trim($_POST['assgn_marks']);
		$data['tdatetime'] = date('Y-m-d H:i:s');
		$data['user_id'] = $_SESSION['userData']['userId'];
		if($this->Course_model->insertStudentAssgnMark($data)){
			$res = array('status'=>'success', 'msg'=>'has been added.');
		}else{
			$res = array('status'=>'error', 'msg'=>'addtion has failed.');
		}
		echo json_encode($res);
	}
	
	/*****************************INSTITUTE/STREAM**********************************/
	public function progOrgStrm()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Institute/Stream | SVIST - Teacher';
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['institute'] = $this->Member->getProgramOrg($data['prog_id']);
			$data['stream'] = $this->Member->getProgramStreams($data['prog_id']);
			
			$this->loadTeacherViews("prog-orgstream", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function cuORG()
	{
		$ori = $_FILES['avatar']['name'];
		$tmp = $_FILES['avatar']['tmp_name'];
		$thmb = uniqid().$ori;

		$data_img = $_POST['crop_img']; 
		if($data_img!=""){
			list($type, $data_img) = explode(';', $data_img);
			list(, $data_img)      = explode(',', $data_img);	
		}
		$userId = $_SESSION['userData']['userId'];
		$data['title'] = trim($_POST['org_title']);
		$data['website'] = trim($_POST['org_web']);
		$data['contact_info'] = trim($_POST['org_contact']);
		if($ori!=""){
			$data['logo'] = $thmb;
		}
		$data['user_id'] = $userId;
		if($_POST['org_id']==""){
			$data['add_date'] = date('Y-m-d H:i:s');
			
			$orgid = $this->Member->insertProOrg($data);
			if($orgid){
				$data1['org_id'] = $orgid;
				$data1['program_id'] = $_POST['iprog_id'];
				$data1['add_date'] = date('Y-m-d H:i:s');
				$flag = $this->Member->insertProMapOrg($data1);
				if($ori!=""){
					if($data_img!=''){
						file_put_contents('./assets/img/institute/'.$thmb, base64_decode($data_img));
						//file_put_contents('./public/image/profile_pic/lg/'.$thmb, base64_decode($data_img));
					}
				}
			}else{
				$flag = 0;
			}
		}else{
			$flag = $this->Member->updateProOrg($data, $_POST['org_id']);
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/institute/'.$thmb, base64_decode($data_img));
					//file_put_contents('./public/image/profile_pic/lg/'.$thmb, base64_decode($data_img));
				}
			}
		}
		echo $flag;
	}
	public function getOrgById()
	{
		$org_id = $_GET['org'];
		$org = $this->Member->getOrgByid($org_id);
		echo json_encode($org);
	}
	public function delOrg()
	{
		$org_id = $_GET['org'];
		$this->Member->deleteProMapOrg($org_id);
		echo $this->Member->deleteProOrg($org_id);
	}
	
	public function cuStream()
	{
		if($_POST['strm_id']==NULL){
			$data['title'] = ucwords(strtolower(trim($_POST['title'])));
			$data['website'] = trim($_POST['web']);
			$data['contact_info'] = trim($_POST['contact']);
			$data['org_id'] = $_POST['org_id'];
			$data['user_id'] = $_SESSION['userData']['userId'];
			$data['add_date'] = date('Y-m-d H:i:s');
			
			$strmid = $this->Member->insertProStream($data);
			if($strmid){
				$data1['stream_id'] = $strmid;
				$data1['program_id'] = $_POST['prog_id'];
				$data1['add_date'] = date('Y-m-d H:i:s');
				$flag = $this->Member->insertProMapStream($data1);
			}else{
				$flag = 0;
			}
		}else{
			$data['title'] = ucwords(strtolower(trim($_POST['title'])));
			$data['website'] = trim($_POST['web']);
			$data['contact_info'] = trim($_POST['contact']);
			$data['org_id'] = $_POST['org_id'];
			$data['user_id'] = $_SESSION['userData']['userId'];
			$flag = $this->Member->updateProStream($data, $_POST['strm_id']);
		}
		echo $flag;
	}
	public function getStreamById()
	{
		$strm_id = $_GET['strm'];
		$strm = $this->Member->getStreamByid($strm_id);
		echo json_encode($strm);
	}
	public function delStream()
	{
		$strm_id = $_GET['strm'];
		$this->Member->deleteProMapStrm($strm_id);
		echo $this->Member->deleteProStrm($strm_id);
	}
	/**********************************************************************/
	/*****************************TEACHERS*********************************/
	public function progTeachers()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Teacher | SVIST - Teacher';
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', NULL, 'accepted');

			$this->loadTeacherViews("prog-teachers", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/**********************************************************************/
	/*****************************STUDENTS*********************************/
	public function progStudents()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Students | SVIST - Teacher';
			$data['prog_id'] = base64_decode($_GET['id']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['pstud'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Student', NULL, 'accepted');
			$data['sems'] = $this->Course_model->getAllSemestersByProgId($data['prog_id']);
			$data['ayr'] = $this->Member->getAllActiveAcaYear();
			
			$this->loadTeacherViews("prog-students", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/**********************************************************************/
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
			$j=1;
			$data['title'] = 'Grades';
			$data['pstud'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Student', NULL, 'accepted');
			$data['pcwt'] = $this->Course_model->getOnlySubjectNameByCid($cid);
			foreach($data['pstud'] as $stow){
				$stud_id = $stow->id;
				$data['setGrade_'.$j] = $this->Course_model->getAllAssignmentMarksByCid($cid, $stud_id);
				$j++;
			}
			//
		}else if($page=='quiz'){
			$data['title'] = 'Quiz';
		}else if($page=='schedule'){
			$data['title'] = 'Schedule Classes';
			$data['schClass'] = $this->Course_model->getScheduleClassByCid($cid, $userid);
		}else if($page=='doubts'){
			$data['title'] = 'Doubts';
			$data['stud_dbs'] = $this->Course_model->getStudentDoubtsByCid($cid, $userid);
		}else if($page=='students'){
			$data['title'] = 'Student List';
			$data['pstud'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Student', NULL, 'accepted');
		}else if($page=='teachers'){
			$data['title'] = 'Teacher List';
			$data['pprof'] = $this->Member->getAllRequestByIdRole($data['prog_id'], 'Teacher', NULL, 'accepted');
		}else if($page=='attendance'){
			$data['title'] = 'Attendance';
			$userid = $_SESSION['userData']['userId'];
			$f = new DateTime('first day of this month');
			$t = new DateTime();
			$from = $f->format('Y-m-d');
			$to = $t->format('Y-m-d');
			if(isset($_GET['from'])){
				$form = $_GET['from'];
			}
			if(isset($_GET['to'])){
				$to = $_GET['to'];
			}
			$live_classes = $this->Member->getLiveClasses($userid,$from,$to);
			$data['userid'] = $userid;
			$data['live_classes'] = $live_classes;			
			$data['from'] = $from;
			$data['to'] = $to;
		}

		return $this->loadTeacherCourse('course-'.$page, $data);
	}
	
	public function liveClass()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Live Class | SVIST - Teacher';
			$userid = $_SESSION['userData']['userId'];
			//$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$cid = base64_decode(trim($_GET['id']));
			$data['progcourse'] = $this->Member->getProgramCourse($cid);
			$data['sch_class'] = $this->Course_model->getScheduleClassByCid($cid, $userid);
			$data['pstud'] = $this->Member->getAllRequestByIdRole($data['progcourse'][0]->pid, 'Student', NULL, 'accepted');
			$data['cid'] = $cid;
			$data['userid'] = $userid;
			$this->loadTeacherViews("live-class", $this->global, $data, NULL);
		}else{
			redirect(base_url().'login');
		}
	}
	/*****************************PROFILE**********************************/
	public function userProfile()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Profile | SVIST - Teacher';
			$data['udetails'] = $this->Member->getUserDetailsById($userid);
			
			$this->loadTeacherViews("profile", $this->global, $data, NULL);
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
		
		if($this->Member->updateUserAuth($data, $id)){
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/users/'.$thmb, base64_decode($data_img));
					//file_put_contents('./public/image/profile_pic/lg/'.$thmb, base64_decode($data_img));
				}
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
			
			$data['salutation'] = trim($_POST['salutation']);
			$data['organization'] = trim($_POST['org']);
			$data['designation'] = ucwords(strtolower(trim($_POST['designation'])));
			$data['website'] = strtolower(trim($_POST['weblink']));
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
			$this->session->set_flashdata('success', 'Profile details has been updated');
		}else{
			$this->session->set_flashdata('error', 'Profile details updation failed');
		}
		
		redirect(base_url().'Teacher/userProfile');
	}
	/**********************************************************************/
	public function MultipleMailSystem($users, $subject, $message)
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

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";
		foreach ($users as $user) {
		  $mail->addAddress($user['email'], $user['name']);

		  $mail->Body = "<h2>Hello, ".$user['name']."</h2> ".$message."</p>";
		  //$mail->AltBody = "Hello, {$user['name']}! \n How are you?";

		  try {
			  $mail->send();
			  //echo "Message sent to: ({$user['email']}) {$mail->ErrorInfo}\n";
		  } catch (Exception $e) {
			  //echo "Mailer Error ({$user['email']}) {$mail->ErrorInfo}\n";
		  }

		  $mail->clearAddresses();
		}

		return true;//($mail->send())? 1 : $mail->ErrorInfo;
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

	/************************************************************************************************************* */
	public function getAllQBsQuestions(){
		if($this->isLoggedIn()){
			$userid           = $_SESSION['userData']['userId'];
			$catid            = $_GET['catid'];
			$scatid           = $_GET['scatid'];
			$data['sid']      = $_GET['sid'];
			$prv_qbs          = $this->Member->getQuestionBySection($data['sid']);
			$ques_list        = $this->Exam_model->getAllQuestionByUser($userid);
			$data['qbks']     = $this->Exam_model->getQBsByPidCid($catid, $scatid, $userid);
			$data['question'] = array();
			$data['options']  = $this->Exam_model->getAllOptions();
			$pq               = array();
			foreach($prv_qbs as $row){
				array_push($pq, $row->ques_id);
			}
			foreach($ques_list  as $ql){
				if(!empty($pq)){
					if(in_array($ql->id,$pq)){
						$data['question'][] = array(
							'id'               => $ql->id,
							'qb_id'            => $ql->qb_id,
							'type_id'          => $ql->type_id,
							'qbody'            => $ql->qbody,
							'answer'           => $ql->answer,
							'hints'            => $ql->hints,
							'weightage'        => $ql->weightage,
							'difficulty_level' => $ql->difficulty_level,
							'marks'            => $ql->marks,
							'type'             => $ql->type,
							'has'              => 1
						);
					}else{
						$data['question'][] = array(
							'id'               => $ql->id,
							'qb_id'            => $ql->qb_id,
							'type_id'          => $ql->type_id,
							'qbody'            => $ql->qbody,
							'answer'           => $ql->answer,
							'hints'            => $ql->hints,
							'weightage'        => $ql->weightage,
							'difficulty_level' => $ql->difficulty_level,
							'marks'            => $ql->marks,
							'type'             => $ql->type,
							'has'              => 0
						);
					}
					
				}else{
					$data['question'][] = array(
						'id'               => $ql->id,
						'qb_id'            => $ql->qb_id,
						'type_id'          => $ql->type_id,
						'qbody'            => $ql->qbody,
						'answer'           => $ql->answer,
						'hints'            => $ql->hints,
						'weightage'        => $ql->weightage,
						'difficulty_level' => $ql->difficulty_level,
						'marks'            => $ql->marks,
						'type'             => $ql->type,
						'has'              => 0
					);
				}
			}
			// echo "<pre>";
			// print_r($data['question']);exit;
			return $this->load->view('teacher_exam/add-edit-question', $data);
		}else{
			redirect(base_url());
		}
	}

	public function saveAddUpdateQuestion(){
		$resp              = array('status' => '');
		$sid               = $_POST['sid'];
		$question          = !empty($_POST['ques']) ? $_POST['ques'] : array() ;
		$question_bank     = !empty($_POST['qbank']) ?$_POST['qbank'] : array();
		$previous_question = $this->Member->getQuestionBySection($sid);
		$previous_bank     = $this->Member->getQuestionBankBySection($sid);

		$db_ques           = array();
		$db_qbank          = array();
		$resultOne         = false;
		$resultTwo         = false;
		$dataQues          = array();
		$dataQbank         = array();

		foreach($previous_question as $qrow){
			array_push($db_ques, $qrow->ques_id);
		}
		foreach($previous_bank as $qbrow){
			array_push($db_qbank, $qbrow->qb_id);
		}

		if(!empty($db_ques)){
			if(count(array_intersect($db_ques,$question)) > 0){
				if(count($db_ques) > count($question)){

					if(!empty(array_diff($question,array_intersect($question,$db_ques)))){
						$i=1;
						foreach(array_diff($question,array_intersect($question,$db_ques)) as $qrow){
							$dataQues[] = array(
								'ques_id'          => $qrow,
								'section_id'       => $sid,
								'type_id'          => 1,
								'create_date_time' =>  date('Y-m-d H:i:s'),
								'rank'             => $i,
								'status'           => true,
								'flag'             => true
							);
							$i++;
						}
						if($this->Member->newQuestionAddOfSection($dataQues) && $this->Member->updateQuestionOfSection(implode(",",array_diff($db_ques,$question)), $sid)){
							$resultOne = true;
						}else{
							$resultOne = false;
						}
					}else{
						if($this->Member->updateQuestionOfSection(implode(",",array_diff($db_ques,$question)), $sid)){
							$resultOne = true;
						}else{
							$resultOne = false;
						}
					}

				}elseif(count($db_ques) < count($question)){
					
					if(!empty(array_diff($db_ques, array_intersect($db_ques,$question)))){
						$i=1;
						foreach(array_diff($question,$db_ques) as $qrow){
							$dataQues[] = array(
								'ques_id'          => $qrow,
								'section_id'       => $sid,
								'type_id'          => 1,
								'create_date_time' =>  date('Y-m-d H:i:s'),
								'rank'             => $i,
								'status'           => true,
								'flag'             => true
							);
							$i++;
						}
						if($this->Member->newQuestionAddOfSection($dataQues) && $this->Member->updateQuestionOfSection(implode(",",array_diff($db_ques, array_intersect($db_ques,$question))), $sid)){
							$resultOne = true;
						}else{
							$resultOne = false;
						}
					}else{
						$i=1;
						foreach(array_diff($question,$db_ques) as $qrow){
							$dataQues[] = array(
								'ques_id'          => $qrow,
								'section_id'       => $sid,
								'type_id'          => 1,
								'create_date_time' =>  date('Y-m-d H:i:s'),
								'rank'             => $i,
								'status'           => true,
								'flag'             => true
							);
							$i++;
						}
						if($this->Member->newQuestionAddOfSection($dataQues)){
							$resultOne = true;
						}else{
							$resultOne = false;
						}
					}

				}else{
					if(!empty(array_diff($question,$db_ques))){
						$i=1;
						foreach(array_diff($question,$db_ques) as $qrow){
							$dataQues[] = array(
								'ques_id'          => $qrow,
								'section_id'       => $sid,
								'type_id'          => 1,
								'create_date_time' =>  date('Y-m-d H:i:s'),
								'rank'             => $i,
								'status'           => true,
								'flag'             => true
							);
							$i++;
						}
						if($this->Member->newQuestionAddOfSection($dataQues) && $this->Member->updateQuestionOfSection(implode(",",array_diff($db_ques,$question)), $sid)){
							$resultOne = true;
						}else{
							$resultOne = false;
						}
					}else{
						$resultOne = true;
					}
				}

			}else{
				if(!empty($question)){
					$i=1;
					foreach($question as $qrow){
						$dataQues[] = array(
							'ques_id'          => $qrow,
							'section_id'       => $sid,
							'type_id'          => 1,
							'create_date_time' =>  date('Y-m-d H:i:s'),
							'rank'             => $i,
							'status'           => true,
							'flag'             => true
						);
						$i++;
					}

					if($this->Member->newQuestionAddOfSection($dataQues) && $this->Member->updateQuestionOfSection(implode(",",$db_ques), $sid)){
						$resultOne = true;
					}else{
						$resultOne = false;
					}
				}else{
					if($this->Member->updateQuestionOfSection(implode(",",$db_ques), $sid)){
						$resultOne = true;
					}else{
						$resultOne = false;
					}
				}
			}
		}else{
			$i=1;
			foreach($question as $qrow){
				$dataQues[] = array(
					'ques_id'          => $qrow,
					'section_id'       => $sid,
					'type_id'          => 1,
					'create_date_time' =>  date('Y-m-d H:i:s'),
					'rank'             => $i,
					'status'           => true,
					'flag'             => true
				);
				$i++;
			}

			if($this->Member->newQuestionAddOfSection($dataQues)){
				$resultOne = true;
			}else{
				$resultOne = false;
			}
		}
		/************/
		if(!empty($db_qbank)){
			if(count(array_intersect($db_qbank,$question_bank)) > 0){
				if(count($db_qbank) > count($question_bank)){
					if(!empty(array_diff($question_bank,array_intersect($question_bank,$db_qbank)))){
						foreach(array_diff($question_bank,array_intersect($question_bank,$db_qbank))as $qbrow){
							$dataQbank[] = array(
								'qb_id'          => $qbrow,
								'sec_id'         => $sid,
								'add_datetime'   =>  date('Y-m-d H:i:s'),
							);
						}
						if($this->Member->newQuestionBankAddOfSection($dataQbank) && $this->Member->updateQuestionBankOfSection(implode(",",array_diff($db_qbank,$question_bank)), $sid)){
							$resultTwo = true;
						}else{
							$resultTwo = false;
						}
					}else{
						if($this->Member->updateQuestionBankOfSection(implode(",",array_diff($db_qbank,$question_bank)), $sid)){
							$resultTwo = true;
						}else{
							$resultTwo = false;
						}
					}
				}elseif(count($db_qbank) < count($question_bank)){
					if(!empty(array_diff($db_qbank, array_intersect($db_qbank,$question_bank)))){
						foreach(array_diff($question_bank,$db_qbank)as $qbrow){
							$dataQbank[] = array(
								'qb_id'          => $qbrow,
								'sec_id'         => $sid,
								'add_datetime'   =>  date('Y-m-d H:i:s'),
							);
						}
						if($this->Member->newQuestionBankAddOfSection($dataQbank) && $this->Member->updateQuestionBankOfSection(implode(",",array_diff($db_qbank, array_intersect($db_qbank,$question_bank))), $sid)){
							$resultTwo = true;
						}else{
							$resultTwo = false;
						}
					}else{
						foreach(array_diff($question_bank,$db_qbank)as $qbrow){
							$dataQbank[] = array(
								'qb_id'          => $qbrow,
								'sec_id'         => $sid,
								'add_datetime'   =>  date('Y-m-d H:i:s'),
							);
						}
						if($this->Member->newQuestionBankAddOfSection($dataQbank)){
							$resultTwo = true;
						}else{
							$resultTwo = false;
						}						
					}
				}else{
					if(!empty(array_diff($question_bank,$db_qbank))){
						foreach(array_diff($question_bank,$db_qbank)as $qbrow){
							$dataQbank[] = array(
								'qb_id'          => $qbrow,
								'sec_id'         => $sid,
								'add_datetime'   =>  date('Y-m-d H:i:s'),
							);
						}
						if($this->Member->newQuestionBankAddOfSection($dataQbank) && $this->Member->updateQuestionBankOfSection(implode(",",array_diff($db_qbank, array_intersect($db_qbank,$question_bank))), $sid)){
							$resultTwo = true;
						}else{
							$resultTwo = false;
						}
					}else{
						$resultTwo = true;
					}
				}
			}else{
				if(!empty($question_bank)){
					foreach($question_bank as $qbrow){
						$dataQbank[] = array(
							'qb_id'          => $qbrow,
							'sec_id'         => $sid,
							'add_datetime'   =>  date('Y-m-d H:i:s'),
						);
					}
					if($this->Member->newQuestionBankAddOfSection($dataQbank) && $this->Member->updateQuestionBankOfSection(implode(",",$db_qbank), $sid)){
						$resultTwo = true;
					}else{
						$resultTwo = false;
					}
				}else{
					if($this->Member->updateQuestionBankOfSection(implode(",",$db_qbank), $sid)){
						$resultTwo = true;
					}else{
						$resultTwo = false;
					}
				}
			}
		}else{
			foreach($question_bank as $qbrow){
				$dataQbank[] = array(
					'qb_id'          => $qbrow,
					'sec_id'         => $sid,
					'add_datetime'   =>  date('Y-m-d H:i:s'),
				);
			}
			if($this->Member->newQuestionBankAddOfSection($dataQbank)){
				$resultTwo = true;
			}else{
				$resultTwo = false;
			}
		}


		if($resultOne && $resultTwo){
			$resp['status'] = TRUE;
		}else{
			$resp['status'] = FALSE;
		}
		echo json_encode($resp);
	}
}