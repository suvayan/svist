<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class Admin_model extends CI_Model {
	
	public function __construct(){
        parent::__construct();
    }
    /*===========================================================================================
                                Oganization and Department List
    =============================================================================================*/
    public function getOrganization($userid){
        $this->db->select('id,title, logo');
        //$this->db->where('user_id',$userid);
        return $this->db->get('pro_organization')->result();
    }
	
	public function getStreamsByOrgId($org_id)
	{
		$this->db->select('id,org_id,title,short_name');
        $this->db->where('org_id',$org_id);
		$this->db->order_by('title', 'ASC');
        return $this->db->get('pro_stream')->result(); 
	}
    public function getDepartment($userid){
        $this->db->select('id,org_id,title,short_name');
        $this->db->where('user_id',$userid);
        return $this->db->get('pro_stream')->result();       
    }

    public function getOrgById($id){
        $this->db->select('*');
        $this->db->where('id',$id);
        return $this->db->get('pro_organization')->result();       
    }

    public function getDeptById($id){
        $this->db->select('pro_stream.id,pro_stream.title,pro_stream.short_name,pro_stream.logo,pro_stream.contact_info,pro_stream.details,pro_stream.website,pro_organization.id as org_id,pro_organization.title as organization');
        $this->db->from('pro_stream');
        $this->db->join('pro_organization', 'pro_organization.id = pro_stream.org_id');
        $this->db->where('pro_stream.id',$id);
        return $this->db->get()->row();
    }
	public function getDeptByIdOrg($id, $org_id)
	{
		$this->db->select('ps.*, po.title as po_title')->from('pro_stream ps');
		$this->db->join('pro_organization po', 'po.id=ps.org_id', 'inner');
		$this->db->where('ps.id', $id);
		$this->db->where('ps.org_id', $org_id);
		return $this->db->get()->result();
	}

    public function insertNewDepartment($data){
        return $this->db->insert('pro_stream', $data);
    }

    public function updateNewDepartment($data,$id){
        $this->db->where('id',$id);  
        return $this->db->update('pro_stream', $data);
    }

    public function insertNewOrganization($data){
        return $this->db->insert('pro_organization', $data);        
    }

    public function updateNewOrganization($data,$id){
        $this->db->where('id',$id);  
        return $this->db->update('pro_organization', $data);       
    }
    /*=================================================================================================*/

    /*===========================================================================================
                                            Program
    =============================================================================================*/
	public function getProgSemesters($prog_id)
	{
		$this->db->select('ps.id, ps.title')->from('pro_semister ps');
		$this->db->join('pro_map_sem pms', 'pms.sem_id=ps.id', 'inner');
		$this->db->where('pms.program_id', $prog_id);
		$this->db->order_by('ps.title', 'ASC');
		return $this->db->get()->result();
	}
	public function getProgramCourses($prog_id, $sid)
	{
		$this->db->select('pc.*')->from('pro_course pc');
		$this->db->join('pro_map_course pms', 'pms.course_id=pc.id', 'inner');
		$this->db->where('pms.program_id', $prog_id);
		$this->db->where('pms.sem_id', $sid);
		$this->db->order_by('pc.post_date', 'ASC');
		return $this->db->get()->result();
	}
	public function getFilterProgramsOrgDept($org_id, $dept_id)
	{
		$this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,program.type,pro_organization.id as org_id,pro_organization.title as org_title,pro_stream.id as strm_id,pro_stream.title as stream_title, acayear.yearnm');
        $this->db->from('program');
		$this->db->join('prog_admission', 'prog_admission.prog_id=program.id', 'inner');
		$this->db->join('acayear', 'acayear.sl=prog_admission.aca_year', 'inner');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id', 'left');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id', 'left');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id', 'left');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id', 'left');
        //$this->db->where('program.user_id',$userid);
		if($org_id!='')
		{
			$this->db->where('pro_organization.id',$org_id);
		}
		if($dept_id!='')
		{
			$this->db->where('pro_stream.id',$dept_id);
		}
        return $this->db->get()->result(); 
	}
	
	public function filterPrograsByDAT($dept, $acayear, $ptype)
	{
		$this->db->select('prog.id, prog.title, pa.aca_year')->from('program prog');
		$this->db->join('pro_map_stream pms', 'pms.program_id=prog.id', 'inner');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'inner');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'left');
		if($ptype!=''){
			$this->db->where('prog.type', $ptype);
		}
		$this->db->where('pa.aca_year', $acayear);
		$this->db->where('pms.stream_id', $dept);
		return $this->db->get()->result(); 
	}
	
    public function getAcademicYear(){
        $this->db->select('sl,yearnm');
        return $this->db->get('acayear')->result();
    }

    public function departmentByOrganization($org){
        $this->db->select('id,title');
        $this->db->where('org_id',$org);
        return $this->db->get('pro_stream')->result();
    }

    public function departmentByProgram($id){
        $sql="SELECT id,title FROM pro_stream WHERE org_id = (SELECT org_id FROM pro_map_org WHERE program_id = ".$id.")";
        return $this->db->query($sql)->result();
    }

    public function programTable($userid){
        $this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,pro_organization.title as org_title,pro_stream.title as stream_title');
        $this->db->from('program');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id');
        $this->db->where('program.user_id',$userid);
        return $this->db->get()->result();
    }

    public function programTableByOrg($userid,$org){
        $this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,pro_organization.title as org_title,pro_stream.title as stream_title');
        $this->db->from('program');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id');
        $this->db->where('program.user_id',$userid);
        $this->db->where('pro_organization.id',$org);
        return $this->db->get()->result();       
    }

    public function programTableByOrgAndDept($userid,$org,$dept){
        $this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,pro_organization.title as org_title,pro_stream.title as stream_title');
        $this->db->from('program');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id');
        $this->db->where('program.user_id',$userid);
        $this->db->where('pro_organization.id',$org);
        $this->db->where('pro_stream.id',$dept);
        return $this->db->get()->result();         
    }

    public function programDetailsById($id){
        $this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,program.student_enroll,program.teacher_enroll,program.type,program.overview,program.email,program.mobile,program.total_fee,program.total_credit,program.feetype,prog_admission.aca_year,acayear.sl,acayear.yearnm,pro_organization.id as org_id,pro_organization.title as org_title,prog_admission.discount,prog_admission.apply_type,prog_admission.astart_date,prog_admission.aend_date,prog_admission.criteria,prog_admission.screen_type,pro_stream.id as dept_id,pro_stream.title as dept_title,prog_admission.ptype,prog_admission.total_seat,prog_admission.prog_hrs');
        $this->db->from('program');
        $this->db->join('prog_admission','prog_admission.prog_id=program.id');
        $this->db->join('acayear','acayear.sl=prog_admission.aca_year');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id');
        $this->db->where('program.id',$id);
        return $this->db->get()->row();
    }

	public function getFilterProfessors($org, $dept)
	{
		$this->db->select("ud.id, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name,ud.photo_sm,ud.linkedin_link,ud.about_me, ud.email, ud.phone")->from('user_details ud');
		$this->db->join('user_auth ua','ua.user_id=ud.id', 'inner');
		//$this->db->join('org_map_users omu','omu.user_id=ud.id', 'left');
		//$this->db->join('pro_organization po','po.id=omu.org_id', 'inner');
		//$this->db->join('pro_stream ps','ps.id=omu.strm_id', 'inner');
		$this->db->where('ua.user_type','teacher');
		/*if($org!=NULL){
			$this->db->where('omu.org_id',$org);
		}
		if($dept!=NULL){
			$this->db->where('omu.strm_id',$dept);
		}*/
		$this->db->order_by('ud.first_name', 'ASC');
		return $this->db->get()->result();
	}
	public function getProfDepartments($uid)
	{
		$this->db->select('omu.*, po.title as po_title, ps.title as ps_title')->from('org_map_users omu');
		$this->db->join('pro_organization po','po.id=omu.org_id', 'inner');
		$this->db->join('pro_stream ps','ps.id=omu.strm_id', 'inner');
		$this->db->where('omu.user_id',$uid);
		return $this->db->get()->result();
		/*$this->db->select('ps.title')->from('pro_stream ps');
		$this->db->join('org_map_users omu','omu.strm_id=ps.id', 'inner');
		*/
	}
	
	public function getNonLinkProfessors($org_id, $strm_id, $prog_id)
	{
		//$this->db->select("")->from('user_details ud');
		$sql = "SELECT ud.id, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name FROM user_details ud INNER JOIN user_auth ua ON ua.user_id=ud.id INNER JOIN org_map_users omu ON omu.user_id=ud.id WHERE ud.id NOT IN (SELECT user_id FROM pro_users_role WHERE program_id=".$prog_id." AND role='Teacher') AND ua.user_type='teacher' AND omu.org_id=".$org_id." AND omu.strm_id=".$strm_id;
		return $this->db->query($sql)->result();
	}
	public function getTeachersByPid($pid)
	{
		$this->db->select("ud.id, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name, ud.email, ud.phone, ud.photo_sm, pur.id as pur_id")->from('user_details ud');
		$this->db->join('pro_users_role pur', 'pur.user_id=ud.id', 'inner');
		$this->db->where('pur.program_id', $pid);
		$this->db->where('pur.role', 'Teacher');
		return $this->db->get()->result();
	}
	
    public function teacherList($start,$record){
        $this->db->select("ud.id,CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name,ud.photo_sm,ud.linkedin_link,ud.about_me");
        $this->db->from('user_details ud');
        $this->db->join('user_auth ua','ua.user_id=ud.id');
        $this->db->where('ua.user_type','teacher');
        $this->db->limit($record,$start);
        return $this->db->get()->result();
    }

    public function teacherTotalRow(){
        $this->db->select("*");
        $this->db->from('user_details ud');
        $this->db->join('user_auth ua','ua.user_id=ud.id');
        $this->db->where('ua.user_type','teacher');
        return $this->db->get()->num_rows();       
    }

    public function getTeacherByID($id){
        $this->db->select("*"); 
        $this->db->from('user_details');
        $this->db->where('id',$id);
        return $this->db->get()->result();
    }

    public function teacherUpdate($data,$id){
        $this->db->where('id',$id);  
        if($this->db->update('user_details', $data)){
            return true;
        }else{
            return false;
        }         
    }
	
	public function getUserOrgs($uid)
	{
		$this->db->select('distinct(po.id), po.title')->from('pro_organization po');
		$this->db->join('org_map_users omu', 'omu.org_id=po.id', 'inner');
		$this->db->where('omu.user_id', $uid);
		return $this->db->get()->result();
	}
	
	public function getUserDepts($uid, $oid)
	{
		$this->db->select('ps.id, ps.title, omu.org_id')->from('pro_stream ps');
		$this->db->join('org_map_users omu', 'omu.strm_id=ps.id', 'inner');
		$this->db->where('omu.user_id', $uid);
		$this->db->where('omu.org_id', $oid);
		return $this->db->get()->result();
	}
	
	public function checkExistingOrgLinks($uid, $oid)
	{
		$this->db->where('user_id', $uid);
		$this->db->where('org_id', $oid);
		return $this->db->get('org_map_users')->num_rows();
	}
	public function checkExistingDeptLinks($uid, $oid, $did)
	{
		$this->db->where('user_id', $uid);
		$this->db->where('strm_id', $did);
		$this->db->where('org_id', $oid);
		return $this->db->get('org_map_users')->num_rows();
	}
	
	/*******************************************************************/
	public function getAcademicProgram($type){
		$this->db->select('prog.id,prog.code,prog.title, pa.aca_year, acayear.yearnm')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'inner');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'left');
		//$this->db->where('prog.user_id', $userid);
		if($type!=''){
			$this->db->where('prog.type', $type);
		}
		return $this->db->get()->result();		
	}
	
	public function getFAStudDetails($pid)
	{
		$this->db->select("CONCAT(ud.first_name,' ',ud.last_name) as name, adm.approve_flag, spc.enrollment_no, sps.sem_id, sps.sl as sps_id, sps.spc_id, ps.title")->from('user_auth ud');
		$this->db->join('adm_can_apply adm', 'adm.cand_id=ud.user_id', 'inner');
		$this->db->join('stud_prog_cons spc', 'adm.prog_id=spc.prog_id', 'inner');
		$this->db->join('stud_prog_state sps', 'sps.spc_id=spc.sl', 'left');
		$this->db->join('pro_semister ps', 'ps.id=sps.sem_id', 'left');
		$this->db->where('ud.user_type', 'student');
		$this->db->where('adm.prog_id', $pid);
		$this->db->where('adm.approve_flag', '2');
		$this->db->where('sps.status', true);
		return $this->db->get()->result();
	}
	
	public function getOldStudCoursesList($pid, $sem_id, $sps_id)
	{
		$sql = 'SELECT pc.id, pc.title, pc.type FROM pro_course pc INNER JOIN pro_map_course pmc ON pmc.course_id=pc.id WHERE pc.id IN (SELECT course_id FROM stud_prog_course WHERE sps_id='.$sps_id.') AND pmc.program_id='.$pid.' AND pmc.sem_id='.$sem_id;
		return $this->db->query($sql)->result();
	}
	public function getNewStudCoursesList($pid, $sem_id, $sps_id)
	{
		$sql = 'SELECT pc.id, pc.title, pc.type FROM pro_course pc INNER JOIN pro_map_course pmc ON pmc.course_id=pc.id WHERE pc.id NOT IN (SELECT course_id FROM stud_prog_course WHERE sps_id='.$sps_id.') AND pmc.program_id='.$pid.' AND pmc.sem_id='.$sem_id;
		return $this->db->query($sql)->result();
	}
}