<?php 
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class Subadmin_model extends CI_Model {
	
	public function __construct(){
        parent::__construct();
    }

    public function getOrganizationName($id){
        $this->db->where('id',$id);
        return $this->db->get('pro_organization')->result();
    }

    public function getDepartmentByOrganization($org_id){
        $this->db->where('org_id',$org_id);
        return $this->db->get('pro_stream')->result();        
    }

    public function deleteDepartment($id){
        $this->db-> where('id', $id);
        $this->db-> delete('pro_stream');
    }

    public function teacherListByOrganization($org_id){
		$this->db->select("ud.id, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name,ud.photo_sm,ud.linkedin_link,ud.about_me, ud.email, ud.phone")->from('user_details ud');
		$this->db->join('user_auth ua','ua.user_id=ud.id', 'inner');
		$this->db->join('org_map_users omu','omu.user_id=ud.id', 'left');
		$this->db->where('ua.user_type','teacher');
        $this->db->where('omu.org_id',$org_id);
		return $this->db->get()->result();        
    }

    public function getDepartmentByOrganizationAndTeacher($org_id){
        $this->db->select('omu.*, ps.title as ps_title, ps.id as ps_id')->from('org_map_users omu');
		$this->db->join('pro_stream ps','ps.id=omu.strm_id', 'inner');
		$this->db->where('omu.org_id',$org_id);
		return $this->db->get()->result();
    }

	public function getUserDepts($uid){
		$this->db->select('ps.id, ps.title, omu.org_id')->from('pro_stream ps');
		$this->db->join('org_map_users omu', 'omu.strm_id=ps.id', 'inner');
		$this->db->where('omu.user_id', $uid);
		return $this->db->get()->result();
	}

	public function getFilterProgramsOrgDept($org_id){
		$this->db->select('program.id as pro_id,program.title,program.code,program.category,program.start_date,program.end_date,program.duration,program.dtype,program.type,pro_organization.id as org_id,pro_organization.title as org_title,pro_stream.id as strm_id,pro_stream.title as stream_title, acayear.yearnm');
        $this->db->from('program');
		$this->db->join('prog_admission', 'prog_admission.prog_id=program.id', 'inner');
		$this->db->join('acayear', 'acayear.sl=prog_admission.aca_year', 'inner');
        $this->db->join('pro_map_org','program.id=pro_map_org.program_id', 'left');
        $this->db->join('pro_map_stream','program.id=pro_map_stream.program_id', 'left');
        $this->db->join('pro_organization','pro_organization.id=pro_map_org.org_id', 'left');
        $this->db->join('pro_stream','pro_stream.id=pro_map_stream.stream_id', 'left');
        $this->db->where('pro_organization.id',$org_id);
        return $this->db->get()->result(); 
	}

    public function departmentByProgram($id){
        $sql="SELECT pro_stream.id as id,title FROM pro_stream INNER JOIN pro_map_stream ON pro_map_stream.stream_id = pro_stream.id WHERE pro_map_stream.program_id=".$id;
        return $this->db->query($sql)->result();
    }

	public function getNonLinkProfessors($strm_id, $prog_id){
		$sql = "SELECT ud.id, CONCAT(ud.salutation,' ',ud.first_name,' ',ud.last_name) as name FROM user_details ud INNER JOIN user_auth ua ON ua.user_id=ud.id INNER JOIN org_map_users omu ON omu.user_id=ud.id WHERE ud.id NOT IN (SELECT user_id FROM pro_users_role WHERE program_id=".$prog_id." AND role='Teacher') AND ua.user_type='teacher' AND omu.strm_id=".$strm_id;
		return $this->db->query($sql)->result();
	}
}