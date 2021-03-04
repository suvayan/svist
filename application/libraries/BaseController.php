<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class BaseController extends CI_Controller {
	protected $name = '';
	//protected $email = '';
	protected $photo = '';
	protected $userId = '';
	protected $global = array ();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginModel');
	}
	
	function isLoggedIn() {
		$isLoggedIn = $this->session->userdata ( 'userData' )['isLoggedIn'];
		
		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			return false;
			redirect ( base_url() );
		} else {
			return true;
		}
	}
	
	public function response($data = NULL) {
		$this->output->set_status_header ( 200 )->set_content_type ( 'application/json', 'utf-8' )->set_output ( json_encode ( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) )->_display ();
		exit ();
	}
	
	function logout() {
		if($this->session->has_userdata('userData')){
			$lt_id = $_SESSION['userData']['lt_id'];
			$userid = $_SESSION['userData']['userId'];
			$data['logout_datetime'] = date('Y-m-d H:i:s');
			$data['status'] = 0;
			$where = 'sl='.$lt_id.' AND user_id='.$userid;
			$this->LoginModel->updateData('login_track', $data, $where);
			
			$this->session->sess_destroy ();
			redirect ( base_url() );
		}else{
			$this->session->sess_destroy ();
			redirect ( base_url() );
		}
	}
	
	function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){
        $this->load->view('home/header', $headerInfo);
        $this->load->view('home/'.$viewName, $pageInfo);
        $this->load->view('home/footer', $footerInfo);
    }
	/*----------------------------------------------------------------------*/
	function loadUserViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('users/header', $headerInfo);
        $this->load->view('users/'.$viewName, $pageInfo);
        $this->load->view('users/footer', $footerInfo);
    }
	function loadUserCourse($viewName = "", $pageInfo = NULL){

        $this->load->view('student_course/'.$viewName, $pageInfo);
    }
	/*----------------------------------------------------------------------*/
	function loadTeacherViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('teachers/header', $headerInfo);
        $this->load->view('teachers/'.$viewName, $pageInfo);
        $this->load->view('teachers/footer', $footerInfo);
    }
	function loadTeacherCourse($viewName = "", $pageInfo = NULL){

        $this->load->view('teacher_course/'.$viewName, $pageInfo);
    }
	function loadTeacherExam($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('teachers/header', $headerInfo);
        $this->load->view('teacher_exam/'.$viewName, $pageInfo);
        $this->load->view('teachers/footer', $footerInfo);
    }
	/*----------------------------------------------------------------------*/
	function loadAdminViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('admin/header', $headerInfo);
        $this->load->view('admin/'.$viewName, $pageInfo);
        $this->load->view('admin/footer', $footerInfo);
    }
	/*----------------------------------------------------------------------*/
	function loadSubAdminViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('sub_admin/header', $headerInfo);
        $this->load->view('sub_admin/'.$viewName, $pageInfo);
        $this->load->view('sub_admin/footer', $footerInfo);
    }
}

?>