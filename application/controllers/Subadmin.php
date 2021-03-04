<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Subadmin extends BaseController {
	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Member');
		$this->load->model('Exam_model');
		$this->load->model('Course_model');
		$this->load->model('LoginModel');
        $this->load->model('Subadmin_model');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
    }  
    
    public function index(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$this->global['pageTitle'] = 'Dashboard | SVIST - Sub-Admin';
			$data = array();
            $data['org_name'] = $this->Subadmin_model->getOrganizationName($org_id);
			$this->loadSubAdminViews("index", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	} 

	public function getOrganizationName(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$data = array();
            $data['purpose']    = $_POST['job'];
            $data['org_name'] = $this->Subadmin_model->getOrganizationName($org_id);
			return $this->load->view('sub_admin/org-dept-list-add-edit',$data);
		}else{
			redirect(base_url());
		}
	}

    public function getDepartmentByOrganization(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$data = array();
            $data['purpose']    = $_POST['job'];
            $data['org']        = $this->Subadmin_model->getOrganizationName($org_id);
            $data['department'] = $this->Subadmin_model->getDepartmentByOrganization($org_id);
			return $this->load->view('sub_admin/org-dept-list-add-edit',$data);
		}else{
			redirect(base_url());
		}        
    }

	public function organizationEditModal(){
		if($this->isLoggedIn()){
			$data            = array();
			$id              = $_POST['id'];
            $data['purpose'] = $_POST['job'];
			$data['org']     = $this->Admin_model->getOrgById($id);
			return $this->load->view('sub_admin/org-dept-list-add-edit',$data);
		}else{
			redirect(base_url());
		}
	}

	public function departmentAddEditModal(){
		if($this->isLoggedIn()){
			$org_id          = (int)$_POST['org_id'];
			$po_title 	     = trim($_POST['po_title']);
			$id              = $_POST['id'];
			$data['purpose'] = $_POST['job'];
			$obj             = array('id'=>0, 'org_id'=>$org_id, 'po_title'=>$po_title, 'title'=>"", 'short_name'=>"", 'user_id'=>"", 'website'=>"", 'details'=>"", 'contact_info'=>"", 'logo'=>"");
			if($id==null){
				$data['dept'][0] = (object)$obj;
			}else{
				$data['dept'] = $this->Admin_model->getDeptByIdOrg($id, $org_id);
			}
			return $this->load->view('sub_admin/org-dept-list-add-edit',$data);
		}else{
			redirect(base_url());
		}
	}

	public function updateOrganization(){
		if($this->isLoggedIn()){
			$id =  (int)$_POST['id'];
			$userid = $_SESSION['userData']['userId'];
			
			$ori = $_FILES['avatar']['name'];
			$tmp = $_FILES['avatar']['tmp_name'];
			$thmb = uniqid().$ori;
			
			$data_img = $_POST['crop_img']; 
			if($data_img!=""){
				list($type, $data_img) = explode(';', $data_img);
				list(, $data_img)      = explode(',', $data_img);	
			}
			
			$data['title']        = trim($_POST['title']);
			$data['user_id']      = $userid;;
			$data['website']      = trim($_POST['weburl']);
			$data['details']      = trim($_POST['details']);
			$data['contact_info'] = trim($_POST['contact']);
			$data['logo'] = ($ori!="")? $thmb : trim($_POST['logo']);
			
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/institute/'.$thmb, base64_decode($data_img));
				}
			}
			$data['last_updated']   = date('Y-m-d H:i:s');
			echo $this->Admin_model->updateNewOrganization($data,$id);
		}else{
			redirect(base_url());
		}		
	}

	public function cuDepartment(){

		if($this->isLoggedIn()){
			$id = (int)$_POST['id'];
			$data['title']        = trim($_POST['title']);
			$data['user_id']      = $_SESSION['userData']['userId'];;
			$data['website']      = trim($_POST['weburl']);
			$data['details']      = trim($_POST['details']);
			$data['contact_info'] = trim($_POST['contact']);
			$data['org_id']       = $_POST['org_id'];
			$data['short_name']   = trim($_POST['short_name']);
			
			if($id==0){
				$data['add_date']     = date('Y-m-d H:i:s');
				echo $this->Admin_model->insertNewDepartment($data);
			}else{
				$data['last_updated']   = date('Y-m-d H:i:s');
				echo $this->Admin_model->updateNewDepartment($data,$id);
			}
		}else{
			redirect(base_url());
		}
	}

	public function deleteDepartment(){
		if($this->isLoggedIn()){
			$id = $_POST['id'];
			echo $this->Subadmin_model->deleteDepartment($id);
		}else{
			redirect(base_url());
		}
	}
//****************************************************************************************************************************************************************/

	public function teacherMaster(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$this->global['pageTitle'] = 'Dashboard | SVIST - Sub-Admin';
			$data = array();
            $data['organization'] = $this->Subadmin_model->getOrganizationName($org_id);
			$data['department']   = $this->Subadmin_model->getDepartmentByOrganization($org_id);
			$this->loadSubAdminViews("teacher-master", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
	}

	public function teacherList(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$data = array();
			$data['purpose'] = $_POST['job'];
			$teachList = $this->Subadmin_model->teacherListByOrganization($org_id);
			$deptList  = $this->Subadmin_model->getDepartmentByOrganizationAndTeacher($org_id);
			$data['teacherList'] = array();
			$idCheck = array();
			$i = 0;
			if(!empty($teachList)){
				foreach($teachList as $tech){
					if(!in_array($tech->id,$idCheck)){
						$data['teacherList'][$i] = array(
							'id'    => $tech->id,
							'photo' => $tech->photo_sm,
							'name'  => $tech->name,
							'email' => $tech->email,
							'phone' => $tech->phone,
						);

						if(!empty($deptList)){
							foreach($deptList as $dept){
								if($dept->user_id == $tech->id){
									$data['teacherList'][$i]['department'][] = $dept->ps_title;
								}
							}
						}
						$i++;
					}
					array_push($idCheck,$tech->id);
				}
			}
			return $this->load->view('sub_admin/teacher-list',$data);
		}else{
			redirect(base_url());
		}		
	}

	public function addEditTeacher(){
		if($this->isLoggedIn()){
			$obj = array('id'=>0, 'salutation'=>"", 'first_name'=>"", 'last_name'=>"", 'photo_sm'=>"", 'email'=>"", 'phone'=>"", 'linkedin_link'=>"", 'about_me'=>"");
			$org_id = $_SESSION['userData']['org_id'];
			$id     = (isset($_GET['id']))? base64_decode($_GET['id']) : NULL;
			if($id == NULL){
				$this->global['pageTitle'] = 'Add Teacher | SVIST - Sub-Admin';
				$data['oper'] = 'Add';
				$data['prof'][0] = (object)$obj;
			}else{
				$this->global['pageTitle'] = 'Update Teacher | SVIST - Sub-Admin';
				$data['oper'] = 'Update';
				$data['prof'] = $this->Admin_model->getTeacherByID($id);
			}
			$data['department']   = $this->Subadmin_model->getDepartmentByOrganization($org_id);
			$this->loadSubAdminViews("add-edit-teacher", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}

	public function cuTeacher(){
		if($this->isLoggedIn()){
			$ori = $_FILES['avatar']['name'];
			$tmp = $_FILES['avatar']['tmp_name'];
			$thmb = uniqid().$ori;
			
			$data_img = $_POST['crop_img']; 
			if($data_img!=""){
				list($type, $data_img) = explode(';', $data_img);
				list(, $data_img)      = explode(',', $data_img);	
			}
			
			$this->load->helper('string');
			$vcode = random_string('numeric', 6);
			$uid = $_POST['uid'];
			$org_id = $_SESSION['userData']['org_id']; 
			$streams = $_POST['dept'];
			$scount = count($streams);
			$fname = ucwords(strtolower(trim($_POST['fname'])));
			$lname = ucwords(strtolower(trim($_POST['lname'])));
			$email = strtolower(trim($_POST['email']));
			$password = trim($_POST['passwd']);
			$phone = trim($_POST['phone']);
			$type = trim($_POST['user_type']);
			
			if($ori!=""){
				if($data_img!=''){
					file_put_contents('./assets/img/users/'.$thmb, base64_decode($data_img));
				}
			}
			$data['salutation'] = $_POST['salutation'];
			$data['first_name'] = trim($fname);
			$data['last_name'] = trim($lname);
			$data['email'] = $email;
			$data['phone'] = $phone;
			$data['photo_sm'] = ($ori!="")? $thmb : trim($_POST['photo_sm']);
			$data['linkedin_link'] = trim($_POST['linkedin']);
			$data['about_me'] = trim($_POST['about_me']);
			
			$data1['first_name'] = trim($fname);
			$data1['last_name'] = trim($lname);
			$data1['phone'] = $phone;
			$data1['email'] = $email;
			$data1['photo_sm'] = ($ori!="")? $thmb : trim($_POST['photo_sm']);
			
			$data1['user_type'] = $type;
			if($uid==0){
				$user = $this->LoginModel->checkValidUser($email);
				if(empty($user)){
					$subject = 'SVIST Registration';
					$msg = '<html><body>Hello '.trim($fname." ".$lname).',<br><br><h4 style="text-align:center">Thank you for Signing Up at svist.billionskills.com</h4><hr><br>Please click <a href="'.base_url().'Login/userVerification/?email='.base64_encode($email).'&vercode='.base64_encode($vcode).'" target="_blank">here</a> to verify your email and complete your registration.<hr></body></html>';
					if($this->MailSystem($email, '', $subject, $msg)){
						
						$data['created_date_time'] = date('Y-m-d H:i:s');
						
						$userid = $this->LoginModel->insertUserDRetId($data);
						if($userid){
							$data1['user_id'] = $userid;
							$data1['password'] = md5($password);
							$data1['status'] = true;
							$data1['verification_code'] = $vcode;
							$data1['verification_status'] = true;
							$data1['create_date_time'] = date('Y-m-d H:i:s');
							$this->LoginModel->insertUserAData($data1);
							
							for($i=0; $i<$scount; $i++){
								$data2[$i]['org_id'] = $org_id;
								$data2[$i]['strm_id'] = $streams[$i];
								$data2[$i]['user_id'] = $userid;
								$data2[$i]['add_datetime'] = date('Y-m-d H:i:s');
								$this->LoginModel->insertData('org_map_users', $data2[$i]);
							}
							$this->session->set_flashdata('errors', 'The teacher has been added successfully.');
						}
					}else{
						$this->session->set_flashdata('errors', 'Mail system error, something went wrong.');
					}
				}else{
					$this->session->set_flashdata('errors', 'This teacher is already beend added. Check the teacher list.');
				}
			}else{
				$where = 'id='.$uid;
				$data['modified_date_time'] = date('Y-m-d H:i:s');
				$this->LoginModel->updateData('user_details', $data, $where);
				
				$where = 'user_id='.$uid;
				$data1['modified_date_time'] = date('Y-m-d H:i:s');
				$this->LoginModel->updateData('user_auth', $data1, $where);
				
				$this->session->set_flashdata('errors', 'The teacher has been updated successfully.');
			}
			redirect(base_url().'Subadmin/teacherMaster');
		}else{
			redirect(base_url());
		}
	}

	public function orgDepTeacher(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$uid = base64_decode($_GET['id']);
			$this->global['pageTitle'] = 'Update Teacher | SVIST - Admin';
			$data['prof'] = $this->Admin_model->getTeacherByID($uid);
			$org   = $this->Subadmin_model->getOrganizationName($org_id);
			$dept = $this->Subadmin_model->getUserDepts($uid);
			$data['ord_dept'] = array();
			if(!empty($dept)){
				$data['ord_dept']=array(
					'uid'       => $uid,
					'org_id'    => $org[0]->id,
					'org_title' => $org[0]->title
				);
				foreach($dept as $row){
					if($org[0]->id == $row->org_id){
						$data['ord_dept']['dept'][] = $row->title;
					}
				}
			}
			// echo"<pre>";
			// print_r($data['ord_dept']);
			$data['department']   = $this->Subadmin_model->getDepartmentByOrganization($org_id);
			$this->loadSubAdminViews('edit-teach-dept', $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
	}

	public function removeUserOrgLinks(){
		$uid = $_GET['uid'];
		$oid = $_GET['oid'];
		$where = 'user_id='.$uid.' AND org_id='.$oid;
		echo $this->Exam_model->deleteData('org_map_users', $where);
	}

	public function cuOrgTeacher(){
		if($this->isLoggedIn()){
			$userid = $_POST['userid'];
			$org_id = $_SESSION['userData']['org_id'];
			$streams = $_POST['dept']; 
			$scount = count($streams);

			if($org_id!='' && $scount>0){
				$chk_org = $this->Admin_model->checkExistingOrgLinks($userid, $org_id);
				if($chk_org==0){
					for($i=0; $i<$scount; $i++){
						$data2[$i]['org_id'] = $org_id;
						$data2[$i]['strm_id'] = $streams[$i];
						$data2[$i]['user_id'] = $userid;
						$data2[$i]['add_datetime'] = date('Y-m-d H:i:s');
						$this->LoginModel->insertData('org_map_users', $data2[$i]);
					}
				}else{
					for($i=0; $i<$scount; $i++){
						$dept_id = $streams[$i];
						$chk_dept = $this->Admin_model->checkExistingDeptLinks($userid, $org_id, $dept_id);
						if($chk_dept==0){
							$data2[$i]['org_id'] = $org_id;
							$data2[$i]['strm_id'] = $dept_id;
							$data2[$i]['user_id'] = $userid;
							$data2[$i]['add_datetime'] = date('Y-m-d H:i:s');
							$this->LoginModel->insertData('org_map_users', $data2[$i]);
						}
					}
				}
			}else{
				$this->session->set_flashdata('errors', 'No organization/department has been selected');
			}
			redirect(base_url().'Subadmin/orgDepTeacher/?id='.base64_encode($userid));
		}else{
			redirect(base_url());
		}
	}

	public function ChangeStudPassword(){
		$uid = base64_decode($_GET['id']);
		$data['password'] = md5(trim($_GET['pass']));
		
		$where = 'user_id='.$uid;
		echo $this->Exam_model->updateData('user_auth', $data, $where);
	}
/***********************************************************************************************************************************************************/

	public function programMaster(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$this->global['pageTitle'] = 'Program | SVIST - Sub-Admin';
			$data = array();
			$this->loadSubAdminViews("program-master", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
	}

	public function programList(){
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			$data = array();
			$data['progs'] = $this->Subadmin_model->getFilterProgramsOrgDept($org_id);
			return $this->load->view('sub_admin/program-list',$data);
		}else{
			redirect(base_url());
		}		
	}

	public function addEditProgram(){
		$obj = array('id'=>"0",'code'=>"",'title'=>"",'type'=>"",'category'=>"",'duration'=>"",'start_date'=>"",'end_date'=>"",'user_id'=>"",'status'=>"",'total_fee'=>"",'fee_details'=>"",'total_credit'=>"",'overview'=>"",'email'=>"",'mobile'=>"",'facebook'=>"",'linkedin'=>"",'twitter'=>"",'student_enroll'=>"",'teacher_enroll'=>"", 'feetype'=>"", 'dtype'=>"", 'stream_id'=>0, 'org_id'=>0, 'apply_type'=>"", 'astart_date'=>"", 'aend_date'=>"", 'criteria'=>"", 'ptype'=>"", 'placement'=>"", 'apply_status'=>"", 'sem_type'=>"", 'discount'=>0, 'prog_hrs'=>0, 'total_seat'=>0, 'aca_year'=>0);
		$dbp = array('id'=>"", 'title'=>"");
		$prog_id = (isset($_GET['id']))? base64_decode($_GET['id']) : null;
		if($this->isLoggedIn()){
			$org_id = $_SESSION['userData']['org_id'];
			if(isset($_GET['id'])){
				$this->global['pageTitle'] = 'Update Program | SVIST - Admin';
				$data['submit'] = 'Update';
				$data['prog'] = $this->Member->getProgramById($prog_id);
				$data['dbp']  = $this->Subadmin_model->departmentByProgram($prog_id);
			}else{
				$this->global['pageTitle'] = 'Add Program | SVIST - Admin';
				$data['submit'] = 'Add';
				$data['prog'][0] = (object)$obj;
				$data['dbp'][0]  = (object)$dbp;
			}
			
			$data['department'] = $this->Subadmin_model->getDepartmentByOrganization($org_id);
			// echo "<pre>";
			// print_r($data['department']);
			$data['acd_year'] = $this->Admin_model->getAcademicYear();
			$this->loadSubAdminViews("program-form", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}			
	}

	public function insertEditNewProgram(){
		if($this->isLoggedIn()){
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
				
				/*$data1['program_id'] = $prog;
				$data1['user_id'] = $userid;
				$data1['role'] = 'Teacher';
				$data1['status'] = 'accepted';
				$data1['add_date'] = date('Y-m-d H:i:s');
				$this->Member->insertUserRole($data1);*/
				
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
				$data5['org_id'] = $_SESSION['userData']['org_id'];
				$data5['program_id'] = $prog;
				$data5['add_date'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('pro_map_org', $data5);
				$data6['stream_id'] = $_POST['dept'];
				$data6['program_id'] = $prog;
				$data6['add_date'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('pro_map_stream', $data6);
				
				redirect(base_url().'Subadmin/programMaster');
			}else{
				$where = 'program_id='.$pid;
				$this->Exam_model->deleteData('pro_map_stream', $where);
				$data['last_updated'] = date('Y-m-d H:i:s');
				$chk_adm = $this->Member->checkAvailProgAdmission($pid);
				$this->Member->updateProgram($data, $pid);
				if($chk_adm==0){
					$data2['prog_id'] = $pid;
					$this->Member->insertAdmission($data2);
				}else{
					$this->Member->updateAdmission($data2, $pid);
				}
				
				$data5['org_id'] = $_SESSION['userData']['org_id'];
				//$data5['add_date'] = date('Y-m-d H:i:s');
				$this->Exam_model->updateData('pro_map_org', $data5, $where);
				$data6['stream_id'] = $_POST['dept'];
				$data6['program_id'] = $pid;
				$data6['add_date'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('pro_map_stream', $data6);
				
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
				
				redirect(base_url().'Subadmin/programMaster');
			}
		}else{
			redirect(base_url());
		}
	}

	public function viewProgCourse(){

		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'View Course | SVIST - Admin';
			$userid = $_SESSION['userData']['userId'];
			$data['prog_id'] = base64_decode($_GET['pid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['psems'] = $this->Admin_model->getProgSemesters($data['prog_id']);
			$i=1;
			foreach($data['psems'] as $row)
			{
				$sid = $row->id;
				$data['procourse_'.$i] = $this->Admin_model->getProgramCourses($data['prog_id'], $sid);
				$i++;
			}
			
			
			$this->loadSubAdminViews("prog-courses", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}

	public function addCourse(){
		if($this->isLoggedIn()){
			$prog_id = base64_decode($_GET['prog']);
			$obj = array('id'=>0, 'prog_id'=>$prog_id, 'title'=>"", 'start_date'=>"", 'end_date'=>"", 'total_credit'=>"", 'syllabus'=>"", 'overview'=>"", 'importance'=>"", 'lec'=>"", 'tut'=>"", 'prac'=>"", 'type'=>"", 'c_code'=>"", 'sem_id'=>"");
			$data['sems'] = $this->Course_model->getAllSemestersByProgId($prog_id);
			if(isset($_GET['cid'])){
				$this->global['pageTitle'] = 'Update Course | SVIST - Admin';
				$cid = base64_decode($_GET['cid']);
				$data['cd'] = $this->Course_model->getCourseDetailsById($cid);
			}else{
				$this->global['pageTitle'] = 'Add Course | SVIST - Admin';
				$data['cd'][0] = (object)$obj;
			}
			$this->loadSubAdminViews("add-course", $this->global, $data , NULL);
		}else{
			redirect(base_url());
		}
	}

	public function deleteProgCourse(){
		if($this->isLoggedIn()){
			$id = $_GET['id'];
			$where = 'id='.$id;
			$where2 = 'course_id='.$id;
			$this->Exam_model->deleteData('pro_map_course', $where2);
			echo $this->Exam_model->deleteData('pro_course', $where);
		}else{
			redirect(base_url());
		}
	}

	public function viewProgTeachers(){
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Teachers | SVIST - Admin';
			$data['prog_id'] = base64_decode($_GET['pid']);
			$org_id = base64_decode($_GET['org']);
			$strm_id = base64_decode($_GET['strm']);
			$data['nlprofs'] = $this->Admin_model->getNonLinkProfessors($org_id, $strm_id, $data['prog_id']);
			//print_r($data['nlprofs']); exit;
			//$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$this->loadSubAdminViews("prog-teachers", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}


	public function linkedTeacherList(){
		if($this->isLoggedIn()){
			$pid = $_GET['pid'];
			$profs = $this->Admin_model->getTeachersByPid($pid);
			$i=1;
			foreach($profs as $row){
				echo '<tr>
							<td>'.$i.'</td>
							<td><img src="'.base_url().$row->photo_sm.'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="avatar-dp mr-2 pull-left" width="80"/>
							<div class="td-name">'.$row->name.'</div></td>
							<td>'.$row->email.'</td>
							<td>'.$row->phone.'</td>
							<td><a href="javascript:removeTeacher('.$row->pur_id.');" title="Remove"><i class="material-icons">delete</i></a></td>
						</tr>';
				$i++;
			}
		}else{
			redirect(base_url());
		}
	}




	public function linkProgTeachers(){
		$resp  =array('status'=>false, 'errors'=>'Something went wrong.');
		$pid = $_POST['prog_id'];
		if(isset($_POST['nlprofs'])){
			$profs = $_POST['nlprofs'];
			$cprof = count($profs);
			if($cprof!=0){
				for($i=0; $i<$cprof; $i++)
				{
					$data[$i]['program_id'] = $pid;
					$data[$i]['role'] = 'Teacher';
					$data[$i]['add_date'] = date('Y-m-d H:i:s');
					$data[$i]['user_id'] = $profs[$i];
					$data[$i]['status'] = 'accepted';
					$this->Exam_model->insertData('pro_users_role', $data[$i]);
				}
				$resp  =array('status'=>true);
			}else{
				$resp  =array('status'=>false, 'errors'=>'No teachers selected.');
			}
		}else{
			$resp  =array('status'=>false, 'errors'=>'No teachers selected.');
		}
		echo json_encode($resp);
	}










	public function MailSystem($to, $cc, $subject, $message){
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
		$mail->clearAddresses();
	}
}