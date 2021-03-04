<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class LoginModel extends CI_Model {
	
	public function insertData($tablename, $data)
	{
		return $this->db->insert($tablename, $data);
	}
	public function insertDataRetId($tablename, $data)
	{
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}
	public function updateData($tablename, $data, $where)
	{
		$this->db->where($where);
		return $this->db->update($tablename, $data);
	}
	public function deleteData($tablename, $where)
	{
		$this->db->where($where);
		return $this->db->update($tablename);
	}
	
	public function checkChngPassStat($email, $vcode)
	{
		$this->db->where('user_id', $email);
		$this->db->where('verification_code', $vcode);
		return $this->db->get('b2c_verification_code')->result();
	}
	
	public function checkValidUser($email)
	{
		$this->db->where('email', $email);
		return $this->db->get('user_auth')->result();
	}
	
	public function checkVerifyUser($email)
	{
		$this->db->where('email', $email);
		$this->db->where('verification_status', true);
		return $this->db->get('user_auth')->result();
	}
	
	public function checkExistingApply($email, $prog)
	{
		$this->db->where('user_email', $email);
		$this->db->where('program_id', $prog);
		$this->db->where('itype', 'applied');
		return $this->db->get('pro_user_invite')->result();
	}
	
	public function insertUserDRetId($data)
	{
		$this->db->insert('user_details', $data);
		return $this->db->insert_id();
	}
	
	public function insertUserAData($data)
	{
		return $this->db->insert('user_auth', $data);
	}
	
	public function insertUserAcademic($data)
	{
		return $this->db->insert('b2c_user_academic', $data);
	}
	
	public function insertUserSkills($data)
	{
		return $this->db->insert('b2c_user_skill', $data);
	}
	
	public function insertUserAdmission($data)
	{
		return $this->db->insert('adm_can_apply', $data);
	}
	
	public function insertProgUserRole($data)
	{
		return $this->db->insert('pro_users_role', $data);
	}
	
	public function checkVerifuStatus($email, $vercode)
	{
		$this->db->where('verification_status', true);
		$this->db->where('email', $email);
		$this->db->where('verification_code', $vercode);
		return $this->db->get('user_auth')->num_rows();
	}
	public function updateVerifyStatus($email, $vercode)
	{
		$this->db->set('verification_status', true);
		$this->db->where('email', $email);
		$this->db->where('verification_code', $vercode);
		return $this->db->update('user_auth');
	}
	
	public function getProgramsFiltered($title, $cat)
	{
		$this->db->select('prog.*, pa.*, acayear.yearnm')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'inner');
		if($title!=null){
			$this->db->like('prog.title', $title, 'both');
		}
		if($cat!=null){
			$this->db->where('prog.category', $cat);
		}
		$this->db->where('prog.type', '1');
		$this->db->where('prog.status', 'approved');
		$this->db->order_by('prog.title', 'ASC');
		return $this->db->get()->result();
	}
	public function getProgramById($id)
	{
		$this->db->select('prog.*, pa.*, acayear.yearnm')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'inner');
		$this->db->where('prog.id', $id);
		$this->db->where('prog.status', 'approved');
		return $this->db->get()->result();
	}
	public function getProgramInfoById($id)
	{
		$this->db->select("prog.*, pa.*, concat(ud.first_name,' ', ud.last_name) as uname, pmo.org_id, pms.stream_id")->from('program prog');
		$this->db->join('user_details ud', 'ud.id=prog.user_id', 'inner');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->join('pro_map_org pmo', 'pmo.program_id=prog.id', 'inner');
		$this->db->join('pro_map_stream pms', 'pmo.program_id=prog.id', 'inner');
		$this->db->where('prog.id', $id);
		$this->db->where('prog.status', 'approved');
		return $this->db->get()->result();
	}
	
	public function getAllAcayear()
	{
		$this->db->order_by('yearnm', 'DESC');
		return $this->db->get('acayear')->result();
	}
	public function getAllDegrees()
	{
		$this->db->order_by('short', 'ASC');
		return $this->db->get('degree')->result();
	}
	public function getAllSkills()
	{
		$this->db->order_by('name', 'ASC');
		return $this->db->get('skills')->result();
	}
	public function getAllStreams()
	{
		$this->db->order_by('title', 'ASC');
		return $this->db->get('stream')->result();
	}
	
	public function getAllSemsByProgId($prog_id)
	{
		$this->db->select('ps.*')->from('pro_semister ps');
		$this->db->join('pro_map_sem pms', 'pms.sem_id=ps.id', 'inner');
		$this->db->where('pms.program_id', $prog_id);
		$this->db->order_by('ps.title', 'ASC');
		return $this->db->get()->result();
	}
	
	public function getProgSemCourses($sem_id, $prog_id)
	{
		$this->db->select('pc.*')->from('pro_course pc');
		$this->db->join('pro_map_course pmc', 'pmc.course_id=pc.id', 'inner');
		$this->db->where('pmc.program_id', $prog_id);
		$this->db->where('pmc.sem_id', $sem_id);
		return $this->db->get()->result();
	}
	
	public function getAllOrganizations()
	{
		$this->db->order_by('title', 'ASC');
		return $this->db->get('pro_organization')->result();
	}
	public function getAllStreamsByOrgId($org_id)
	{
		$this->db->where('org_id', $org_id);
		$this->db->order_by('title', 'ASC');
		return $this->db->get('pro_stream')->result();
	}
	public function getUsersDepts($user_id)
	{
		$this->db->select('org_id, strm_id');
		$this->db->where('user_id', $user_id);
		return $this->db->get('org_map_users')->result();
	}
	
	public function getAllProgramsByDeptId($dept, $acayear)
	{
		$this->db->select('prog.id, prog.title, acayear.yearnm')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'inner');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'inner');
		$this->db->join('pro_map_stream pms', 'pms.program_id=prog.id', 'inner');
		$this->db->where('prog.type', '2');
		$this->db->where('pa.aca_year', $acayear);
		$this->db->where('pms.stream_id', $dept);
		return $this->db->get()->result();
	}
	
}