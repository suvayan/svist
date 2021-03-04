<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Admin extends BaseController {

	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Member');
		$this->load->model('Exam_model');
		$this->load->model('Course_model');
		$this->load->model('LoginModel');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
    }   
    /******************************************************************************** */ 

    /*===========================================================================================
                                Oganization and Department List
    =============================================================================================*/

    public function index(){

		if($this->isLoggedIn()){
			
			$this->global['pageTitle'] = 'Dashboard | SVIST - Admin';
			
			$this->loadAdminViews("index", $this->global, NULL, NULL);
		}else{
			redirect(base_url());
		}
	} 
	/***************************************************************************************/

	public function organizationAndDepartment()
	{
		$userid = $_SESSION['userData']['userId'];
		$data['orgs'] = $this->Admin_model->getOrganization($userid);
		$i=1;
		foreach($data['orgs'] as $row){
			$org_id = $row->id;
			$data['depts_'.$i] = $this->Admin_model->getStreamsByOrgId($org_id);
			$i++;
		}
		//$data['org_department'] = $this->Admin_model->getDepartment($userid);
		$this->load->view('admin/orgdept-lists', $data);
	}
	
	public function organizationModalCall()
	{
		$id = $_GET['id'];
		$obj = array('id'=>0, 'title'=>"", 'user_id'=>"", 'website'=>"", 'details'=>"", 'contact_info'=>"", 'logo'=>"");
		if($id==NULL){
			$data['org'][0] = (object)$obj;
		}else{
			$data['org'] = $this->Admin_model->getOrgById($id);
		}
		return $this->load->view('admin/add-edit-org', $data);
	}
	public function cuOrganization()
	{
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
		if($id==0){
			$data['add_date'] = date('Y-m-d H:i:s');
			echo $this->Admin_model->insertNewOrganization($data);
		}else{
			$data['last_updated']   = date('Y-m-d H:i:s');
			echo $this->Admin_model->updateNewOrganization($data,$id);
		}
	}
	
	public function departmentModalCall()
	{
		$userid       = $_SESSION['userData']['userId'];
		$org_id       = (int)$_GET['org_id'];
		$po_title 	  = trim($_GET['po_title']);
		$id           = $_GET['id'];
		$obj = array('id'=>0, 'org_id'=>$org_id, 'po_title'=>$po_title, 'title'=>"", 'short_name'=>"", 'user_id'=>"", 'website'=>"", 'details'=>"", 'contact_info'=>"", 'logo'=>"");
		if($id==null){
			$data['dept'][0] = (object)$obj;
		}else{
			$data['dept'] = $this->Admin_model->getDeptByIdOrg($id, $org_id);
		}
		return $this->load->view('admin/add-edit-dept', $data);
	}
	public function cuDepartment()
	{
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
	}
	public function deleteDepartment()
	{
		$id = $_GET['id'];
		$where = 'sl='.$id;
		echo $this->Exam_model->deleteData('pro_stream', $where);
	}
    /*=====================================================================================*/
	public function departmentByOrganization(){
		$org = $this->input->get('org');
		//$dept = $this->input->get('dept');
		$department = $this->Admin_model->departmentByOrganization($org);
		$output = '<option value="">Select department <span class="text-danger">*</span></option>';
		if($department){
			foreach($department as $row){
				$output .= '<option value='.$row->id.'>'.$row->title.'</option>';
			}
		}
		echo $output;
    }
    /*===========================================================================================
                                            Program
    =============================================================================================*/
    public function programList(){

		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Program List | SVIST - Admin';
			$data = array();
			$data['organization'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("org-program", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
    } 
    /************************************************************************************************* */
    public function programTable()
	{
		$org_id = $_GET['org_id'];
		$dept_id = $_GET['dept_id'];
		$data['progs'] = $this->Admin_model->getFilterProgramsOrgDept($org_id, $dept_id);
		return $this->load->view('admin/program-list', $data);
	}
	
	public function addEditProgram()
	{
		$obj = array('id'=>"0",'code'=>"",'title'=>"",'type'=>"",'category'=>"",'duration'=>"",'start_date'=>"",'end_date'=>"",'user_id'=>"",'status'=>"",'total_fee'=>"",'fee_details'=>"",'total_credit'=>"",'overview'=>"",'email'=>"",'mobile'=>"",'facebook'=>"",'linkedin'=>"",'twitter'=>"",'student_enroll'=>"",'teacher_enroll'=>"", 'feetype'=>"", 'dtype'=>"", 'stream_id'=>0, 'org_id'=>0, 'apply_type'=>"", 'astart_date'=>"", 'aend_date'=>"", 'criteria'=>"", 'ptype'=>"", 'placement'=>"", 'apply_status'=>"", 'sem_type'=>"", 'discount'=>0, 'prog_hrs'=>0, 'total_seat'=>0, 'aca_year'=>0);
		$prog_id = (isset($_GET['id']))? base64_decode($_GET['id']) : null;
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$userid = $_SESSION['userData']['userId'];
			if(isset($_GET['id'])){
				$this->global['pageTitle'] = 'Update Program | SVIST - Admin';
				$data['prog'] = $this->Member->getProgramById($prog_id);
			}else{
				$this->global['pageTitle'] = 'Add Program | SVIST - Admin';
				$data['prog'][0] = (object)$obj;
			}
			$data['organization'] = $this->Admin_model->getOrganization($userid);
			$data['department'] = ($prog_id != NULL )? $this->Admin_model->departmentByProgram($prog_id) : '';
			$data['acd_year'] = $this->Admin_model->getAcademicYear();
			$this->loadAdminViews("program-form", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		} 
	}
	public function insertEditNewProgram()
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
			$data5['org_id'] = $_POST['org'];
			$data5['program_id'] = $prog;
			$data5['add_date'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('pro_map_org', $data5);
			$data6['stream_id'] = $_POST['dept'];
			$data6['program_id'] = $prog;
			$data6['add_date'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('pro_map_stream', $data6);
			
			redirect(base_url().'Admin/addEditProgram');
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
			
			$data5['org_id'] = $_POST['org'];
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
			
			redirect(base_url().'Admin/programList');
		}
	}
	/*=====================================================================================================================*/

	/*===========================================================================================================================
									                Courses
	============================================================================================================================*/
	
	public function viewProgCourse()
	{
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
			
			
			$this->loadAdminViews("prog-courses", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function addCourse()
	{
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
        
        $this->loadAdminViews("add-course", $this->global, $data , NULL);
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
			$this->global['pageTitle'] = 'Course Details | SVIST - Admin';
			$data['prog_id'] = base64_decode($_GET['prog']);
			$data['cid'] = base64_decode($_GET['cid']);
			$data['prog'] = $this->Member->getProgramById($data['prog_id']);
			$data['cdetails'] = $this->Course_model->getCourseDetailsById($data['cid']);
			
			$this->loadAdminViews("course-details", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function deleteProgCourse()
	{
		$id = $_GET['id'];
		$where = 'id='.$id;
		$where2 = 'course_id='.$id;
		$this->Exam_model->deleteData('pro_map_course', $where2);
		echo $this->Exam_model->deleteData('pro_course', $where);
	}
	
	public function viewProgTeachers()
	{
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
			$this->loadAdminViews("prog-teachers", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/*=====================================================================================================================*/

	/*===========================================================================================================================
									                Teachers
	============================================================================================================================*/

	public function teacherMaster(){
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Teacher Master | SVIST - Admin';
			$this->loadAdminViews("teacher-master", $this->global, NULL, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function teacherList()
	{
		$org = $_GET['org'];
		$dept = $_GET['dept'];
		$data['profs'] = $this->Admin_model->getFilterProfessors($org, $dept);
		$i=1;
		foreach($data['profs'] as $row){
			$uid = $row->id;
			$data['strms_'.$i] = $this->Admin_model->getProfDepartments($uid);
			$i++;
		}
		return $this->load->view('admin/teacher-list', $data);
	}
	public function linkedTeacherList()
	{
		$pid = $_GET['pid'];
		$profs = $this->Admin_model->getTeachersByPid($pid);
		$i=1;
		foreach($profs as $row)
		{
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
	}
	public function linkProgTeachers()
	{
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
	public function addEditTeacher()
	{
		$obj = array('id'=>0, 'salutation'=>"", 'first_name'=>"", 'last_name'=>"", 'photo_sm'=>"", 'email'=>"", 'phone'=>"", 'linkedin_link'=>"", 'about_me'=>"");
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$id = (isset($_GET['id']))? base64_decode($_GET['id']) : NULL;
			if($id==NULL){
				$this->global['pageTitle'] = 'Add Teacher | SVIST - Admin';
				$data['prof'][0] = (object)$obj;
			}else{
				$this->global['pageTitle'] = 'Update Teacher | SVIST - Admin';
				$data['prof'] = $this->Admin_model->getTeacherByID($id);
			}

			//print_r($data['prof']);exit;
			
			$data['org'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("add-edit-prof", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function cuOrgTeacher()
	{
		$userid = $_POST['userid'];
		$org_id = $_POST['org']; 
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
		redirect(base_url().'Admin/orgDepTeacher/?id='.base64_encode($userid));
	}
	public function orgDepTeacher()
	{
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$uid = base64_decode($_GET['id']);
			$this->global['pageTitle'] = 'Update Teacher | SVIST - Admin';
			$data['prof'] = $this->Admin_model->getTeacherByID($uid);
			$data['org'] = $this->Admin_model->getOrganization($userid);
			$data['uorg'] = $this->Admin_model->getUserOrgs($uid);
			$i=1;
			foreach($data['uorg'] as $row){
				$oid = $row->id;
				$data['udept_'.$i] = $this->Admin_model->getUserDepts($uid, $oid);
				$i++;
			}
			$this->loadAdminViews('edit-org-prof', $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function removeUserOrgLinks()
	{
		$uid = $_GET['uid'];
		$oid = $_GET['oid'];
		$where = 'user_id='.$uid.' AND org_id='.$oid;
		echo $this->Exam_model->deleteData('org_map_users', $where);
		//echo true;
	}
	public function cuTeacher()
	{
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
		$org_id = $_POST['org']; 
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
		redirect(base_url().'Admin/addEditTeacher');
	}
	public function ChangeStudPassword()
	{
		$uid = base64_decode($_GET['id']);
		$data['password'] = md5(trim($_GET['pass']));
		
		$where = 'user_id='.$uid;
		echo $this->Exam_model->updateData('user_auth', $data, $where);
	}
	/*********************************************************************************************************************************/
	/*==========================================================================================================================*/

	/*===========================================================================================================================
									                Student
	=============================================================================================================================*/
	
	public function studAdmission($type){
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Student  Admission| SVIST.org - Teacher';
			$userid = $_SESSION['userData']['userId'];
			$data['atype'] = $type;
			$data['ptype'] = ($type=='old')? 2 : 1;
			$data['acayear'] = $this->Admin_model->getAcademicYear();	
			$data['orgs'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("stud-admission", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getPrograms()
	{
		$options = '';
		$dept = $_GET['dept'];
		$acayear = $_GET['acayear'];
		$ptype = $_GET['ptype'];
		$progs = $this->Admin_model->filterPrograsByDAT($dept, $acayear, $ptype);
		if(!empty($progs)){
			foreach($progs as $row){
				echo '<option value="'.$row->id.'">'.trim($row->title).'</option>';
			}
		}else{
			$options = '<option value="">No Programs</option>';
		}
		echo $options;
	}
	public function getProgAdmissionList()
	{
		$pid = trim($_GET['pid']);
		$data['atype'] = trim($_GET['atype']);
		$data['prog'] = $this->Member->getProgramById($pid);
		if($data['atype']=='pre'){
			$data['adm_list'] = $this->Member->getStudAdmissionListBYAF($pid);
			$data['ayr'] = $this->Member->getAllActiveAcaYear();
			$i=1;
			foreach($data['adm_list'] as $row){
				$uid = $row->cand_id;
				$data['udaca1_'.$i] = $this->Member->getUserAcademic($uid);
				$i++;
			}
			return $this->load->view('admin/stud-adm-pending', $data);
		}else if($data['atype']=='final'){
			$data['adm_list'] = $this->Member->getStudAdmissionListBYAF($pid);
			$data['sems'] = $this->Course_model->getAllSemestersByProgId($pid);
			$i=1;
			foreach($data['adm_list'] as $row){
				$uid = $row->cand_id;
				$data['uroll_'.$i] = $this->Member->getUserEnrollRoll($uid, $pid);
				$i++;
			}
			return $this->load->view('admin/stud-adm-final', $data);
		}else if($data['atype']=='old'){
			$data['adm_list'] = $this->Member->getStudAdmissionListBYAF($pid);
			$i=1;
			foreach($data['adm_list'] as $row){
				$uid = $row->cand_id;
				$data['uroll_'.$i] = $this->Member->getUserEnrollRoll($uid, $pid);
				$i++;
			}
			return $this->load->view('admin/stud-adm-old', $data);
		}
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
		$subject = trim($prog[0]->title)." (Notice)";
		$message = 'You have been selected in 1st merit list for the program: '.trim($prog[0]->title).'.<br>';
		if($ftype==1){
			$message.='Please pay the amount within last date of Admission or else your appllication will be rejected.';
		}else{
			$message.='Your Course will start from '.date('d/m/Y',strtotime($prog[0]->start_date)).' at 3:00 PM.';
		}
		$message.='<br>Log in to your dashboard <a href="'.base_url().'" target="_blank">here</a> for more details.';
		$message.='<hr><br>All the best.<br>Thanking You,<br>SVIST.org<br><img src="'.base_url().'/assets/img/logo.png" style="width:100px;"/>';
		
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
				$this->Exam_model->insertData('stud_prog_cons', $data1[$i]);
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
		$message = 'The admission for the program: '.trim($prog[0]->title).', in which you have applied, has been rejected.<br>Thanking You,<br>SVIST.org';
		if($this->MultipleMailSystem($users, $subject, $message)){
			//$this->Member->deleteMultipleDataById('adm_can_apply', 'sl', $acp_ids);
			$this->Member->updateMultiAdmApply($acp_ids, 3);
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
		$mcount = 0;
		$atype = $_POST['atype'];
		$pid = $_POST['prog_id2'];
		$acp_ids = $_POST['acp2'];
		$prog = $this->Member->getProgramById($pid);
		$users = $this->Member->getUsersArrayByACPIds($acp_ids);
		$counter = count($acp_ids);
		$subject = trim($prog[0]->title)." (Notice)";
		$user_ids = $this->Member->getUserIdsByACPIds($acp_ids);
		
		if($atype=='old'){
			$this->Member->updateMultiAdmApply($acp_ids, 2);
			for($i=0; $i<$counter; $i++){
				$message = 'Hello '.$users[$i]['name'].'<br>Your application has been accepted.<br>Log in to your dashboard <a href="'.base_url().'" target="_blank">here</a> to start your program.<hr><br>Thanking You,<br>SVIST.org<br><img src="'.base_url().'/assets/img/logo.png" style="width:100px;"/>';
				//$this->MailSystem(trim($users[$i]['email']),'', $subject, $message);
				if($this->MailSystem(trim($users[$i]['email']),'', $subject, $message)){
					$uid = $user_ids[$i]->id;
					$data[$i]['user_id'] = $uid;
					$data[$i]['program_id'] = $pid;
					$data[$i]['add_date'] = date('Y-m-d H:i:s');
					$data[$i]['status'] = 'accepted';
					$data[$i]['role'] = 'Student';
					$this->Member->insertUserRole($data[$i]);
				}
			}
			
			echo '1';
		}else{
			$cids = $this->Member->getCourseIdsByProgid($pid);
			$ftype = $_POST['feetype2'];
			$semid = $_POST['semid'];
			
			if($this->Member->updateMultiAdmApply($acp_ids, 2)){
				for($i=0; $i<$counter; $i++){
					$uid = $user_ids[$i]->id;
					$acpid = $acp_ids[$i];
					$enroll = trim($_POST['enroll_'.$acpid]);
					if($enroll!=""){
						$message = 'Congratulation '.$users[$i]['name'].'<br>Your admission for this program is complete. Your Course will start from '.date('d/m/Y',strtotime($prog[0]->start_date)).'.<br>Your program enrollment number is <strong>'.$enroll.'</strong>.<br>Log in to your dashboard <a href="'.base_url().'" target="_blank">here</a> to start your program.<hr><br>Thanking You,<br>SVIST.org<br><img src="'.base_url().'/assets/img/logo.png" style="width:100px;"/>';
						if($this->MailSystem(trim($users[$i]['email']),'', $subject, $message)){
							$uid = $user_ids[$i]->id;
							$data[$i]['user_id'] = $uid;
							$data[$i]['program_id'] = $pid;
							$data[$i]['add_date'] = date('Y-m-d H:i:s');
							$data[$i]['status'] = 'accepted';
							$data[$i]['role'] = 'Student';
							$this->Member->insertUserRole($data[$i]);
							
							$data1[$i]['enrollment_no'] = $enroll;
							$where = 'sl='.$_POST['spcid_'.$acpid];
							$this->Exam_model->updateData('stud_prog_cons', $data1[$i], $where);
							$data2[$i]['stud_id'] = $uid;
							$data2[$i]['spc_id'] = trim($_POST['spcid_'.$acpid]);
							$data2[$i]['roll_no'] = trim($_POST['roll_'.$acpid]);
							$data2[$i]['aca_year'] = date('Y');
							$data2[$i]['sem_id'] = $semid;
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
					}
				}
			}else{
				echo '2';
			}
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
		$message = 'The admission for the program: '.trim($prog[0]->title).', in which you have applied, has been rejected.<br>Thanking You,<br>SVIST.org';
		if($this->MultipleMailSystem($users, $subject, $message)){
			$this->Member->updateMultiAdmApply($acp_ids, 3);
			echo true;
		}else{
			echo false;
		}
	}
	public function studMaster(){
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Student Master | SVIST.org - Teacher';
			$userid = $_SESSION['userData']['userId'];
			$data   = array();
			$data['acayear'] = $this->Admin_model->getAcademicYear();	
			$data['orgs'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("stud-master", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
	}
	
	/*============================================================================================*/
	public function studCourse()
	{
		if($this->isLoggedIn()){
			$this->global['pageTitle'] = 'Student  Courses| SVIST.org - Teacher';
			$userid = $_SESSION['userData']['userId'];
			$data['acayear'] = $this->Admin_model->getAcademicYear();	
			$data['orgs'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("stud-courses", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	public function getStudCoursesList()
	{
		$pid = $_GET['pid'];
		$data['prog'] = $this->Member->getProgramById($pid);
		$data['studs'] = $this->Admin_model->getFAStudDetails($pid);
		$i=1;
		foreach($data['studs'] as $row){
			$sem_id = $row->sem_id;
			$sps_id = $row->sps_id;
			$data['oldcourse_'.$i] = $this->Admin_model->getOldStudCoursesList($pid, $sem_id, $sps_id);
			$data['newcourse_'.$i] = $this->Admin_model->getNewStudCoursesList($pid, $sem_id, $sps_id);
			$i++;
		}
		return $this->load->view('admin/add-stud-courses', $data);
	}
	public function addStudCourses()
	{
		$rowid = $_POST['rowId'];
		$sps_id = $_POST['sps_'.$rowid];
		$courses = $_POST['scourse_'.$rowid];
		$ccount = count($courses);
		if($ccount>0){
			for($i=0; $i<$ccount; $i++){
				$data[$i]['sps_id'] = $sps_id;
				$data[$i]['course_id'] = $courses[$i];
				$data[$i]['add_date'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('stud_prog_course', $data[$i]);
			}
			echo true;
		}else{
			echo false;
		}
	}
	/*public function getAcademicSemester(){
		$userid = $_SESSION['userData']['userId'];
		$progId = $_POST['programId'];
		$sem_list = $this->Member->getAcademicSemester($progId);
		$output = '<option value="" disabled>Set semester</option>';
		if($sem_list){
			foreach($sem_list as $row){
				$output .='<option value='.$row->sem_id.'>'.$row->title.'</option>';
			}
		}

		echo $output;
	}

	public function studentMasterList(){
		$output       = '';
		$progId       = $_POST['acaprog'];
		$semId        = $_POST['acasem'];
		//$yearId       = $_POST['acayear'];
		$userid       = $_SESSION['userData']['userId'];//,$semId,$yearId
		$student_list = $this->Member->getStudentList($progId);
		if(!empty($student_list)){
			$output = $this->studentMasterTablePrint($student_list, $progId, $semId);
		}
		echo $output;
	}

	public function studentMasterTablePrint($student_list, $progId, $semId){
		$result = "";$i=1;
		
		foreach($student_list as $data){
			$stud_id = $data->stud_id;
			$stud_adm[$i] = $this->Member->getStudProgConsState($stud_id, $progId, $semId);
			if(!empty($stud_adm[$i])){
				//$stud_id = $data->stud_id;
				$spc_id = $stud_adm[$i][0]->spc_id;
				$sps_id = $stud_adm[$i][0]->sps_id;
				$enrollment_no = $stud_adm[$i][0]->enrollment_no;
				$status = $stud_adm[$i][0]->status;
				$sps_cgpa = $stud_adm[$i][0]->sps_cgpa;
				$sps_percent = $stud_adm[$i][0]->sps_percent;
				$totalfees = $stud_adm[$i][0]->totalfees;
				$certificate = trim($stud_adm[$i][0]->certificate);
				$result.= '<tr id="row'.$stud_id.'">
					<td><input type="checkbox" class="checkbox" onClick="clickChangeEvent()" value="'.$spc_id.'"></td>
					<td>'.$data->name.'</td>
					<td>'.$enrollment_no.'</td>
					<td>'.$data->phone.'</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control" id="parce'.$stud_id.'" name="parce" data-id="'.$sps_id.'" value="'.((!empty($sps_percent))? $sps_percent:"").'"> 
						</div>
					</td>
					<td>
						<div class="form-group">
							<input type="text" class="form-control" id="cgpa'.$stud_id.'" name="cgpa"  value="'.((!empty($sps_cgpa))? $sps_cgpa:"").'">
						</div>
					</td>
					<td>'.((intval($totalfees)==0)?'Free':$totalfees).'</td>
					<td>
						<div class="form-group">
							<select class="form-control" id="status'.$stud_id.'" name="status" data-id="'.$spc_id.'">
								<option value="0" '.(($status == 0)?'selected="selected"':"").'>Started</option>
								<option value="1" '.(($status == 1)?'selected="selected"':"").'>Completed</option>
								<option value="2" '.(($status == 2)?'selected="selected"':"").'>Failed</option>
							</select>
						</div>
					</td>
					<td>';
					
					if($certificate!=""){
						if(file_exists('./'.$certificate)){
							$result.= '<a href="'.base_url($certificate).'" target="_blank"><i class="material-icons">cloud_download</i> Click</a>';
						}else{
							if($status == 1){
								$result.= '<a href="javascript:;" onClick="$(`#myFile'.$spc_id.'`).click();"><i class="material-icons">cloud_upload</i> Upload</a>';
							}
						}
					}else{
						if($status == 1){
							$result.= '<a href="javascript:;" onClick="$(`#myFile'.$spc_id.'`).click();"><i class="material-icons">cloud_upload</i> Upload</a>';
						}
					}
				$result.= '<input type="file" class="btn btn-success btn-sm" id="myFile'.$spc_id.'" onchange="certificateUpload('.$spc_id.','.$stud_id.')" style="display:none;" accept="application/pdf, image/jpg, image/png, image/jpeg">
					</td>
					<td><button class="btn btn-success btn-sm" onClick="singleStudentChange('.$stud_id.')">Save</button></td>
				</tr>';
			}
		$i++;
		}
		return $result;
	}
	public function multipleStudentChange(){
		$status = $_POST['status'];
		$spc_id = explode(",",$_POST['spc_id']);
		$data   = array();

		foreach ($spc_id  as $row) {
			$data[] = array(
				'sl' => $row,
				'status' => $status
			);
		}

		echo $this->Member->multipleStudentStatus($data);
	}
	/*----------------------------------------------
	public function singleStudentChange(){
		$output       = '';
		$stud         = $_POST['stud'];
		$percent      = (!empty($_POST['percent']))?(int)($_POST['percent']) : 0;
		$cgpa         = (!empty($_POST['cgpa']))?(int)($_POST['cgpa']) : 0;
		$sps_id       = $_POST['sps_id'];
		$status       = $_POST['status'];
		$spc_id       = $_POST['spc_id'];
		$sem_id       = $_POST['sem_id'];

		$data1['percent'] = $percent;
		$data1['cgpa']    = $cgpa;
		$data2['status']  = $status; 

		$student_list = $this->Member->singleStudentPercentageAndCgpaAndStatus($data1,$data2,$stud,$sps_id,$spc_id,$sem_id);

		if($student_list){
			echo TRUE;
		}else{
			echo FALSE;
		}
	}
	public function getSingleStudentMaster(){
		$id     = $_POST['stud'];
		$data   = $this->Member->getSingleStudent($id);
		$result = '<td>
					<input type="checkbox" class="checkbox" onClick="clickChangeEvent()" value="'.$data->spc_id.'">
				</td>
				<td>'.$data->name.'</td>
				<td>'.$data->enrollment_no.'</td>
				<td>'.$data->phone.'</td>
				<td>
					<div class="form-group">
						<input type="text" class="form-control" id="parce'.$data->stud_id.'" name="parce" data-id="'.$data->sps_id.'" value="'.((!empty($data->sps_percent))?$data->sps_percent:"").'"> 
					</div>
				</td>
				<td>
					<div class="form-group">
						<input type="text" class="form-control" id="cgpa'.$data->stud_id.'" name="cgpa"  value="'.((!empty($data->sps_cgpa))?$data->sps_cgpa:"").'">
					</div>
				</td>
				<td>'.((intval($data->totalfees)==0)?'Free':$data->totalfees).'</td>
				<td>
					<div class="form-group">
						<select class="form-control" id="status'.$data->stud_id.'" name="status" data-id="'.$data->spc_id.'">
							<option value="0" '.(($data->status == 0)?'selected="selected"':"").'>Started</option>
							<option value="1" '.(($data->status == 1)?'selected="selected"':"").'>Completed</option>
							<option value="2" '.(($data->status == 2)?'selected="selected"':"").'>Failed</option>
						</select>
					</div>
				</td>
				<td>';
					$certificate = trim($data->certificate);
					if($certificate!=""){
						if(file_exists('./'.$certificate)){
							$result .= '<a href="'.base_url($certificate).'" target="_blank"><i class="material-icons">cloud_download</i> Click</a>';
						}else{
							if($data->status == 1){
								$result .= '<a href="javascript:;" onClick="$(`#myFile'.$data->spc_id.'`).click();"><i class="material-icons">cloud_upload</i> Upload</a>';
							}
						}
					}else{
						if($data->status == 1){
							$result .= '<a href="javascript:;" onClick="$(`#myFile'.$data->spc_id.'`).click();"><i class="material-icons">cloud_upload</i> Upload</a>';
						}
					}
			$result .= '<input type="file" class="btn btn-success btn-sm" id="myFile'.$data->spc_id.'" onchange="certificateUpload('.$data->spc_id.','.$data->stud_id.')" style="display:none;" accept="application/pdf, image/jpg, image/png, image/jpeg">';
			$result .= '</td>
				<td><button class="btn btn-success btn-sm" onClick="singleStudentChange('.$data->stud_id.')">Save</button></td>
		';
		echo $result;
    }
	/*---------------------------------
	public function studentCertificatesUpload(){
		$tmp = $_FILES['file']['tmp_name'];
		$name = $_FILES['file']['name'];
		if($name!=''){
			move_uploaded_file($tmp, './uploads/certificates/'.$name);
			$data['certificate'] = 'uploads/certificates/'.$name;
			$spc_id    = $_POST['spc_id'];
			return $this->Member->studentCertificatesUpload($data,$spc_id);
		}else{
			return false;
		}
	}*/
	
	/*=====================================================================================================================*/
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
		$mail->clearAddresses();
	}
}