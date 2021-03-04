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
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Dashboard | Magnox Learning+ - Organization';
			$data['organization'] = $this->Admin_model->getOrganization($userid);
			$data['org_department'] = $this->Admin_model->getDepartment($userid);
			$this->loadAdminViews("index", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	} 
	/***************************************************************************************/

	public function organizationAndDepartment(){
		$userid = $_SESSION['userData']['userId'];
		$organization = $this->Admin_model->getOrganization($userid);
		$department   = $this->Admin_model->getDepartment($userid);
		$output       = '';
		if(!empty($organization) || !empty($department)){
			$output = $this->organizationAndDepartmentShow($organization, $department);
		}
		echo $output;
	}
	/*************************************************************************************/

	public function organizationAndDepartmentShow($organization, $department){
		$output = '';
		$i = 1;
		foreach($organization as $org):
			$output .='
				<div class="card-collapse" id="card_'.$i.'">
					<div class="card-header" role="tab" id="heading'.$i.'">
						<h5 class="mb-0">
							<a class="collapsed" data-toggle="collapse" href="#collapse'.$i.'" aria-expanded="false" aria-controls="collapse'.$i.'">
								'.$i.' . '.$org->title.'<i class="material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>
					<div id="collapse'.$i.'" class="collapse" role="tabpanel" aria-labelledby="heading'.$i.'" data-parent="#accordion">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<a href="javascript:organizationModal(`Edit`,'.$org->id.')" class="btn btn-success btn-sm">Update Organization Details</a>
									<a href="javascript:departmentModal(`Add`,'.$org->id.')" class="btn btn-info btn-sm">Add New Department</a>
									<ol type="1">
			';
									foreach($department as $dept):
										if($org->id === $dept->org_id):
											$output .= '
												<li>'.$dept->title.' ('.((!empty($dept->short_name))?$dept->short_name : '').')<a href="javascript:departmentModal(`Edite`,'.$org->id.','.$dept->id.')"><i class="material-icons ml-4 small">create</i></a></li>
											';
										endif;
									endforeach;
			$output .='
									</ol>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		$i++;
		endforeach;
	    echo $output;
	}
	/**************************************************************************************/
	public function organizationModalBody($org = null){
		$output = '
			<div class="row">
				<div class="col-md-6">
					<div class="picture-container">
						<div class="picture">
							<label>
								<img src="'.((!empty($org->logo))? base_url().$org->logo:base_url()."assets/img/image_placeholder.jpg").'" class="picture-src" onerror="this.src=`'.base_url('assets/img/image_placeholder.jpg').'`" id="wizardPicturePreview" title="" width="250"/>
								<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
							</label>
							<input type="hidden" name="crop_img" id="crop_img" value="" />
						</div>
					</div>
					
				</div>
				<div class="col-md-6" id="cropImagePop" style="display:none;">
					<div id="upload-demo" class="center-block"></div>
					<br>
					<button type="button" onClick="cropImageBtn()" class="btn btn-primary">Crop</button>
					<button type="button" class="btn btn-danger btn-link" onClick="$(`#cropImagePop`).hide()">Cancel</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" class="form-control" id="title" value="'.((!empty($org->title))? $org->title:'').'">
						<span id="error_title" class="text-danger small"></span>
					</div>
					<div class="form-group">
						<label for="url">Website</label>
						<input type="url" class="form-control" id="url" value="'.((!empty($org->website))? $org->website:'').'">
						<span id="error_url" class="text-danger small"></span>
					</div>
					<div class="form-group">
						<input type="hidden" class="form-control" id="id" value="'.((!empty($org->id))? $org->id:'').'">
						<input type="hidden" class="form-control" id="logo" value="'.((!empty($org->logo))? $org->logo:'').'">
					</div>
					<div class="form-group">
						<label for="contact">Contact</label><br>
						<textarea class="form-control" id="contact" rows="3"></textarea>
						<script>$("#contact").summernote("code", `'.((!empty($org->contact_info))? $org->contact_info:'').'`);</script>
					</div>
					<div class="form-group">
						<label for="details">Details</label><br>
						<textarea class="form-control" id="details" rows="3"></textarea>
						<script>$("#details").summernote("code", `'.((!empty($org->details))? $org->details:'').'`);</script>
					</div>
				</div>
			</div>
		';
		echo $output;
	}
	/**************************************************************************************/
	public function organizationModalCall(){
		$output = "";
		$id = $_POST['id'];
		if(!empty($id)){
			$org = $this->Admin_model->getOrgById($id);
			$output = $this->organizationModalBody($org);
		}else{
			$output = $this->organizationModalBody();
		}
		echo $output;
	}
	/************************************************************************************* */
	public function organizationInsertUpdate(){
		$resp                 = array('permission'=> ''); 
		$data['title']        = $_POST['title'];
		$data['user_id']      = $_SESSION['userData']['userId'];;
		$data['website']      = $_POST['url'];
		$data['details']      = $_POST['details'];
		$data['contact_info'] = $_POST['contact'];
		$id                   = !empty($_POST['id']) ? $_POST['id'] : '';
		$data['logo']         = !empty($_POST['logo_path'])? $_POST['logo_path'] : '';
		$image                = '';
		$crop_logo            = $_POST['clogo'];
		if(!empty($_FILES['logo']['name'])){
			$tmp   = uniqid().$_FILES['logo']['name'];
			if($crop_logo!=''){
				list($type, $crop_logo) = explode(';', $crop_logo);
				list(, $crop_logo)      = explode(',', $crop_logo);
				if(file_put_contents('./assets/img/institute/'.$tmp, base64_decode($crop_logo))){
					$data['logo'] = 'assets/img/institute/'.$tmp;
				}
			}
		}
		if(!empty($_POST['id'])){
			$data['last_updated']   = date('Y-m-d H:i:s');
			if($this->Admin_model->updateNewOrganization($data,$id)){
				$resp['permission'] = TRUE;
			}else{
				$resp['permission'] = FALSE;
			}
		}else{
			$data['add_date']     = date('Y-m-d H:i:s');
			if($this->Admin_model->insertNewOrganization($data)){
				$resp['permission'] = TRUE;
			}else{
				$resp['permission'] = FALSE;
			}
		}
		echo json_encode($resp);
	}
	/*************************************************************************************/

	public function departmentModalBody($organization,$department=NULL,$id=NULL){
		$output = '
				<div class="row">
					<div class="col-lg-6 col-md-6">
						<div class="picture-container">
							<div class="picture">
								<label>
									<img src="'.((!empty($department->logo))? base_url().$department->logo:base_url()."assets/img/data-uploader-blog.jpg").'" class="picture-src" id="wizardPicturePreview" title="" width="250"/>
									<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
								</label>
								<input type="hidden" name="crop_img" id="crop_img" value="" />
							</div>
						</div>
		';
		if(!empty($organization) && !empty($id)){
			
			$output .= '
						<div class="form-group">
    						<label for="org_id">Organization</label>
							<select class="form-control" id="org_id">
								<option value="'.$department->org_id.'">'.$department->organization.'</option>
						';
						foreach($organization as $org){
							if($org->id == $department->org_id){
								continue;
							}
							$output .= '<option value="'.$org->id.'">'.$org->title.'</option>';
						}
			$output .= '
							</select>
						</div>
					   ';
		}else{
			$output .= '
						<div class="form-group">
							<input type="hidden" class="form-control" id="org_id" value="'.$organization->id.'">
						</div>
			';
		}
		$output .= '
					<div class="form-group">
						<label for="dept_details">Details</label>
						<textarea class="form-control" id="dept_details" rows="3">'.((!empty($department->details))? $department->details:'').'</textarea>
						<script>CKEDITOR.replace("dept_details");</script>
					</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="form-group">
							<label for="title">Title</label>
							<input type="text" class="form-control" id="title" value="'.((!empty($department->title))? $department->title:'').'">
							<span id="error_title" class="text-danger small"></span>
						</div>
						<div class="form-group">
							<label for="title">Short Name</label>
							<input type="text" class="form-control" id="short_name" value="'.((!empty($department->short_name))? $department->short_name:'').'">
						</div>
						<div class="form-group">
							<label for="url">Website</label>
							<input type="url" class="form-control" id="url" value="'.((!empty($department->website))? $department->website:'').'">
						</div>
						<div class="form-group">
							<input type="hidden" class="form-control" id="id" value="'.((!empty($department->id))? $department->id:'').'">
							<input type="hidden" class="form-control" id="logo" value="'.((!empty($department->logo))? $department->logo:'').'">
						</div>
						<div class="form-group">
							<label for="dept_contact">Contact</label><br>
							<textarea class="form-control" id="dept_contact" rows="3">'.((!empty($department->contact_info))? $department->contact_info:'').'</textarea>
							<script>CKEDITOR.replace("dept_contact");</script>
						</div>
					</div>
				</div>
		';
		return $output;
	}

	/**************************************************************************************/
	public function departmentModalCall(){
		$userid       = $_SESSION['userData']['userId'];
		$org_id       = $_POST['org_id'];
		$id           = !empty($_POST['id'])? $_POST['id'] : '';
		$organization = !empty($_POST['id'])? $this->Admin_model->getOrganization($userid) : $this->Admin_model->getOrgById($org_id);
		$department   = !empty($_POST['id'])? $this->Admin_model->getDeptById($id) : '';
		$output       = '';
		if(!empty($organization) || !empty($department)){
			$output = $this->departmentModalBody($organization,$department,$id);
		}
		echo $output;
	}
	/***************************************************************************************/
	public function departmentInsertUpdate(){
		$resp                 = array('permission'=> ''); 
		$data['title']        = $_POST['title'];
		$data['user_id']      = $_SESSION['userData']['userId'];;
		$data['website']      = $_POST['url'];
		$data['details']      = $_POST['details'];
		$data['contact_info'] = $_POST['contact'];
		$data['org_id']       = $_POST['org'];
		$data['short_name']   = $_POST['short_name'];
		$id                   = !empty($_POST['id']) ? $_POST['id'] : '';
		$data['logo']         = !empty($_POST['logo_path'])? $_POST['logo_path'] : '';
		$image                = '';
		$crop_logo            = $_POST['clogo'];
		if(!empty($_FILES['logo']['name'])){
			$tmp   = uniqid().$_FILES['logo']['name'];
			if($crop_logo!=''){
				list($type, $crop_logo) = explode(';', $crop_logo);
				list(, $crop_logo)      = explode(',', $crop_logo);
				if(file_put_contents('./assets/img/institute/'.$tmp, base64_decode($crop_logo))){
					$data['logo'] = 'assets/img/institute/'.$tmp;
				}
			}
		}
		if(!empty($_POST['id'])){
			$data['last_updated']   = date('Y-m-d H:i:s');
			if($this->Admin_model->updateNewDepartment($data,$id)){
				$resp['permission'] = TRUE;
			}else{
				$resp['permission'] = FALSE;
			}
		}else{
			$data['add_date']     = date('Y-m-d H:i:s');
			if($this->Admin_model->insertNewDepartment($data)){
				$resp['permission'] = TRUE;
			}else{
				$resp['permission'] = FALSE;
			}
		}
		echo json_encode($resp);		
	}
    /*=====================================================================================*/

    /*===========================================================================================
                                            Program
    =============================================================================================*/
    public function programList(){

		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Dashboard | Magnox Learning+ - Organization';
			$data = array();
			$data['organization'] = $this->Admin_model->getOrganization($userid);
			$this->loadAdminViews("org-program", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
    } 
    /************************************************************************************************* */
    public function programTable(){
		$result = '';
		$userid = $_SESSION['userData']['userId'];
		$data   = $this->Admin_model->programTable($userid);
		if(!empty($data)){
			$result = $this->showProgramTable($data);
		}
		echo $result;
	}
    /************************************************************************************************* */
    public function programTableByOrg(){
		$result = '';
		$org    = $_POST['data'];
		$userid = $_SESSION['userData']['userId'];
		$data   = $this->Admin_model->programTableByOrg($userid,$org);
		if(!empty($data)){
			$result = $this->showProgramTable($data);
		}
		echo $result;		
    }
    /**************************************************************************************************** */
    public function departmentByOrganization(){
		$org = $this->input->post('org');
		$department = $this->Admin_model->departmentByOrganization($org);
		$output = '<option value="">Select the department</option>';
		if($department){
			foreach($department as $dept){
				$output .= '<option value='.$dept->id.'>'.$dept->title.'</option>';
			}
		}
		echo $output;
    }
    /******************************************************************************************************* */
	public function programTableByOrgAndDept(){
		$result = '';
		$org    = $_POST['org'];
		$dept   = $_POST['dept'];
		$userid = $_SESSION['userData']['userId'];
		$data   = $this->Admin_model->programTableByOrgAndDept($userid,$org,$dept);
		if(!empty($data)){
			$result = $this->showProgramTable($data);
		}
		echo $result;
	}
    /******************************************************************************************************* */
	public function showProgramTable($data){
		$output = '
			<table class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%">
				<thead>            
					<th>Program</th>
					<th>Code</th>
					<th>Organization</th>
					<th>Department</th>
					<th>Category</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Duration</th>
					<th colspan="4">Action</th>  
				</thead>
				<tbody style="overflow-y:scroll; max-height:400px;">
		';
		foreach($data as $row){
			$output .= '
				<tr>
					<td>'.$row->title.'</td>
					<td>'.$row->code.'</td>
					<td>'.$row->org_title.'</td>
					<td>'.$row->stream_title.'</td>
					<td>'.$row->category.'</td>
					<td>'.$row->start_date.'</td>
					<td>'.$row->end_date.'</td>
                    <td>'.$row->duration.'('.$row->dtype.')</td>
                    <td><button class="btn btn-success btn-sm">Courses</button></td>
                    <td><button class="btn btn-success btn-sm">Teachers</button></td>
                    <td><a  href="'.base_url().'Admin/addAndEditProgram/'.base64_encode($row->pro_id).'" class="btn btn-success btn-sm">Edit</a></td>
                    <td><button class="btn btn-success btn-sm">View</button></td>
				</tr>
			';
		}
		$output .= '
				</tbody>
			</table>
		';
		return $output;
    }
    /****************************************************************************************************** */
    public function addAndEditProgram($prog_id=NULL){
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Dashboard | Magnox Learning+ - Organization';
			$data = array();
			$data['organization'] = $this->Admin_model->getOrganization($userid);
            $data['acd_year'] = $this->Admin_model->getAcademicYear();
            $data['form_title'] = (empty($prog_id))? 'Add' : 'Edit';
            $data['prog']       = (!empty($prog_id) && $prog_id != NULL)? $this->Admin_model->programDetailsById(base64_decode($prog_id)) : '';
            $data['department'] = (!empty($prog_id)&& $prog_id != NULL )? $this->Admin_model->departmentByProgram(base64_decode($prog_id)) : '';
            //print_r($this->Admin_model->programDetailsById(base64_decode($prog_id)));exit;
			$this->loadAdminViews("program-form", $this->global, $data, NULL);
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
			$this->global['pageTitle'] = 'Dashboard | Magnox Learning+ - Organization';
			$data = array();
			$this->loadAdminViews("teacher-master", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}
	}
	/*********************************************************************************************************************************/
	public function teacherPagination($page_number,$last_page){
		$output = '<ul class="pagination">';
		if($page_number > 1){
			$output .= '
				<li class="paginate_button page-item previous" id="datatables_previous">
					<a href="javascript:teacherPagination('.($page_number - 1).')" aria-controls="datatables" data-dt-idx="1" tabindex="0" class="page-link">Prev</a>
				</li>
			';
		}
		if($page_number == 1){
			for($i=$page_number;$i<=($page_number+2);$i++){
				if($i == $page_number){
					$output .= '
						<li class="paginate_button page-item active">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';
				}elseif($i <= $last_page){
					$output .= '
						<li class="paginate_button page-item">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';					
				}
			}
		}elseif($page_number != 1 && $page_number < $last_page){
			for($i=($page_number - 1);$i<=($page_number + 1);$i++){
				if($i == $page_number){
					$output .= '
						<li class="paginate_button page-item active">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';					
				}else{
					$output .= '
						<li class="paginate_button page-item">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';					
				}
			}
		}elseif($page_number != 1 && $page_number == $last_page){
			for($i=($last_page - 2);$i<=$last_page;$i++){
				if($i == $page_number){
					$output .= '
						<li class="paginate_button page-item active">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';					
				}elseif($i != 0){
					$output .= '
						<li class="paginate_button page-item">
							<a href="javascript:teacherPagination('.$i.')" aria-controls="datatables" data-dt-idx="2" tabindex="0" class="page-link">'.$i.'</a>
						</li>
					';					
				}
			}
		} 
		if($page_number < $last_page){
			$output .= '
				<li class="paginate_button page-item next" id="datatables_next">
					<a href="javascript:teacherPagination('.($page_number + 1).')" aria-controls="datatables" data-dt-idx="6" tabindex="0" class="page-link">Next</a>
				</li>
			';
		}
		$output .= '</ul>';
		return $output;
	}
	/**********************************************************************************************************************************/
	public function teacherList(){
		$page_number = (!empty($_POST['page'])) ? $_POST['page'] : 1;
		$per_page_record   = (!empty($_POST['record']))? $_POST['record'] : 10;
		$teacher           = '';
		$row               = '';
		$org               = (!empty($_POST['organization']))? $_POST['organization'] : '';
		$dept              = (!empty($_POST['department']))? $_POST['department'] : '';
		if(!empty($org)){

		}elseif(!empty($dept)){

		}else{
			$row = $this->Admin_model->teacherTotalRow();
		}

		$last_page         = ceil($row/$per_page_record);
		if($page_number < 1):
			$page_number = 1;
		elseif($page_number > $last_page):
			$page_number = $last_page;
		endif;
		$start_record      = abs(($page_number - 1) * $per_page_record);	

		if(!empty($org)){

		}elseif(!empty($dept)){

		}else{
			$teacher = $this->Admin_model->teacherList($start_record,$per_page_record);
		}
		// echo "<pre>";
		// print_r($teacher);exit;
		$output = '
						<div class="row">
							<div class="col-sm-12 col-md-6">
								<div class="dataTables_length" id="datatables_length">
									<label>
										Show 
										<select name="datatables_length" aria-controls="datatables" class="custom-select custom-select-sm form-control form-control-sm" id="entries" onchange="showEntries()">
											<option value="10" '.(($per_page_record == 10)?'selected="selected"': '').'>10</option>
											<option value="25" '.(($per_page_record == 25)?'selected="selected"': '').'>25</option>
											<option value="50" '.(($per_page_record == 50)?'selected="selected"': '').'>50</option>
											<option value="100" '.(($per_page_record == 100)?'selected="selected"': '').'>100</option>
										</select>
										entries
									</label>
								</div>
							</div>
						</div>
						<div class="row">
                            <div class="col-sm-12">
                                <table id="datatables" class="table table-striped" cellspacing="0" width="100%" style="width: 100%;" role="grid" aria-describedby="datatables_info">
                                    <thead>
                                        <tr role="row">
                                            <th style="width: 105px;">Image</th>
                                            <th style="width: 210px;">Name</th>
                                            <th style="width: 700px;">About</th>
                                            <th style="width: 350px;">LinkedIn</th>
                                            <th style="width: 30px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
		';
		if(!empty($teacher)){
			foreach($teacher as $tech){
				$output .= '
										<tr>
											<td><img src="'.((!empty($tech->photo_sm))? base_url().$tech->photo_sm : base_url().'assets/img/default-avatar.png').'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" width="100"></td>
											<td>'.$tech->name.'</td>
											<td>'.((!empty($tech->about_me))? $tech->about_me : '').'</td>
											<td>'.((!empty($tech->linkedin_link))? '<a href="'.trim($tech->linkedin_link).'" target="_blank"><i class="fa fa-linkedin"></i> '.$tech->linkedin_link.'</a>' : '').'</td>
											<td><a href="javascript:teacherUpdateModalCall('.$tech->id.','.$page_number.')" class="text-success small"><i class="material-icons">edit</i></a></td>
										</tr>
				';
			}
		}
		$output .= '
									</tbody>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-5"></div>
							<div class="col-sm-12 col-md-7">
								<div class="dataTables_paginate paging_full_numbers" id="datatables_paginate">
		';
		if(!empty($teacher)){
			$output .= $this->teacherPagination($page_number,$last_page);
		}
		$output .= '
								</div>
							</div>
						</div>
		';
		echo $output;
	}


	public function teacherUpdateModalBody($teacher,$page){
		$output = '';
		$output = '
				<div class="row">
					<div class="col-md-6">
						<div class="picture-container">
							<div class="picture">
								<label>
									<img src="'.((!empty($teacher->photo_sm))? base_url().$teacher->photo_sm:base_url()."assets/img/default-avatar.png").'" class="picture-src" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" id="wizardPicturePreview" title="" width="250"/>
									<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
								</label>
								<input type="hidden" name="crop_img" id="crop_img" value="" />
							</div>
						</div>
						
					</div>
					<div class="col-md-6" id="cropImagePop" style="display:none;">
						<div id="upload-demo" class="center-block"></div>
						<br>
						<button type="button" onClick="cropImageBtn()" class="btn btn-primary">Crop</button>
						<button type="button" class="btn btn-danger btn-link" onClick="$(`#cropImagePop`).hide()">Cancel</button>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="form-group">
							<label for="salutation">Organization</label>
							<select class="form-control" id="salutation">
								<option value="">Select Salutation</option>
								<option value="Mr." '.((trim($teacher->salutation)=='Mr.')?'selected="selected"': '').'>Mr</option>
								<option value="Mrs." '.((trim($teacher->salutation)=='Mrs.')?'selected="selected"': '').'>Mrs</option>
								<option value="Ms." '.((trim($teacher->salutation)=='Ms.')?'selected="selected"': '').'>Ms</option>
								<option value="Prof." '.((trim($teacher->salutation)=='Prof.')?'selected="selected"': '').'>Prof</option>
								<option value="Dr." '.((trim($teacher->salutation)=='Dr.')?'selected="selected"': '').'>Dr</option>
							</select>
						</div>

						<div class="form-group">
							<label for="first_name">First Name</label>
							<input type="text" class="form-control" id="first_name" value="'.((!empty($teacher->first_name))? $teacher->first_name:'').'">
							<span id="error_first_name" class="text-danger small"></span>
						</div>
						<div class="form-group">
							<label for="last_name">Last Name</label>
							<input type="text" class="form-control" id="last_name" value="'.((!empty($teacher->last_name))? $teacher->last_name:'').'">
							<span id="error_last_name" class="text-danger small"></span>
						</div>
						<div class="form-group">
							<label for="linkedin">Linkedin</label>
							<input type="text" class="form-control" id="linkedin" value="'.((!empty($teacher->linkedin_link))? $teacher->linkedin_link:'').'">
						</div>
						<div class="form-group">
							<label for="about_me">About Me</label><br>
							<textarea class="form-control" id="about_me" rows="3">'.((!empty($teacher->about_me))? $teacher->about_me:'').'</textarea>
							<script>CKEDITOR.replace("about_me");</script>
						</div>
						<input type="hidden" id="id" value="'.$teacher->id.'">
						<input type="hidden" id="page" value="'.$page.'">
						<input type="hidden" id="path" value="'.$teacher->photo_sm.'">
					</div>
				</div>
		';
		echo $output;
	}

	public function teacherUpdateModalCall(){
		$output = '';
		$id      = $_POST['id'];
		$page    = $_POST['page'];
		$teacher = $this->Admin_model->getTeacherByID($id);
		if(!empty($teacher)){
			$output = $this->teacherUpdateModalBody($teacher,$page);
		}
		echo $output;
	}

	public function teacherUpdate(){
		$resp                  = array('permission'=> ''); 
		$data['first_name']    = $_POST['first_name'];
		$data['last_name']     = $_POST['last_name'];
		$data['salutation']    = $_POST['salutation'];
		$data['linkedin_link'] = $_POST['linkedin'];
		$data['about_me']      = $_POST['about'];
		$id                    = !empty($_POST['id']) ? $_POST['id'] : '';
		$data['photo_sm']      = !empty($_POST['photo_path'])? $_POST['photo_path'] : '';
		$crop_logo             = $_POST['clogo'];
		if(!empty($_FILES['photo_sm']['name'])){
			$tmp   = uniqid().$_FILES['logo']['name'];
			if($crop_logo!=''){
				list($type, $crop_logo) = explode(';', $crop_logo);
				list(, $crop_logo)      = explode(',', $crop_logo);
				if(file_put_contents('./assets/img/users/'.$tmp, base64_decode($crop_logo))){
					$data['photo_sm'] = 'assets/img/users/'.$tmp;
				}
			}
		}
		$data['modified_date_time']   = date('Y-m-d H:i:s');
			if($this->Admin_model->teacherUpdate($data,$id)){
				$resp['permission'] = TRUE;
			}else{
				$resp['permission'] = FALSE;
		}
		echo json_encode($resp);		
	}
}