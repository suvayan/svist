<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class Member extends CI_Model {
	
	public function deleteMultipleDataById($tablename, $pkid, $data)
	{
		$this->db->where_in($pkid, $data);
		return $this->db->delete($tablename);
	}
	
	public function getTotalProgCourses($pid)
	{
		$this->db->where('prog_id', $pid);
		return $this->db->get('pro_course')->num_rows();
	}
	public function getAllUserDeptsByArray($strm_ids)
	{
		$this->db->select('id, title')->from('pro_stream ps');
		//$this->db->join('org_map_users omu', 'omu.strm_id=ps.id', 'inner');
		$this->db->where_in('id', $strm_ids);
		return $this->db->get()->result();
	}
	public function getAllMyPrograms($type, $userid)
	{
		$this->db->select('prog.*, pa.*, prog.user_id as mu_id, pur.user_id AS puserid, pur.role, acayear.yearnm')->from('program prog');
		$this->db->join('pro_users_role pur', 'pur.program_id=prog.id', 'inner');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'inner');
		$this->db->where('pur.role', $type);
		$this->db->where('pur.user_id', $userid);
		$this->db->where('pur.status', 'accepted');
		$this->db->where('prog.status', 'approved');
		$this->db->order_by('prog.date_added', 'DESC');
		return $this->db->get()->result();
	}
	public function getProfProgramCourses($pid)
	{
		$this->db->select('pc.id, pc.title, pc.c_code, pc.type, ps.id as sem_id, ps.title as ps_title')->from('pro_course pc');
		$this->db->join('pro_map_course pmc', 'pmc.course_id=pc.id', 'inner');
		$this->db->join('pro_semister ps', 'ps.id=pmc.sem_id', 'inner');
		$this->db->where('pmc.program_id', $pid);
		return $this->db->get()->result();
	}
	public function getAllStudPrograms($userid)
	{
		$this->db->select('prog.id, prog.title, prog.duration, prog.dtype, prog.category, pa.ptype, acayear.yearnm, adm.approve_flag, spc.sl as spc_id, spc.certificate, spc.status as spc_status')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'inner');
		$this->db->join('adm_can_apply adm', 'adm.prog_id=prog.id', 'inner');
		$this->db->join('stud_prog_cons spc', 'spc.stud_id=adm.cand_id', 'left outer');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'left');
		$this->db->where('adm.cand_id', $userid);
		$this->db->where('prog.status', 'approved');
		$this->db->order_by('adm.apply_datetime', 'DESC');
		return $this->db->get()->result();
	}
	public function getStudProgCons($userid, $pid, $spc_id)
	{
		/*$sql = "select pc.id, pc.title from pro_course pc
		inner join pro_map_course pmc on pmc.course_id=pc.id
		inner join stud_prog_state sps on sps.sem_id=pmc.sem_id
		where pmc.program_id=".$pid,." and pc.id in (select course_id from stud_prog_course spc ) and sps.stud_id=".$userid." and sps.spc_id=".$spc_id." and sps.status=true
		$this->db->select('pc.id, pc.title')-from('pro_course pc');
		$this->db->join('pro_map_course pmc', 'pmc.course_id=pc.id', 'inner');
		$this->db->join('stud_prog_state sps', 'sps.sem_id=pmc.sem_id', 'inner');
		$this->db->where('pmc.program_id', $pid);
		$this->db->where('sps.stud_id', $userid);
		$this->db->where('sps.spc_id', $spc_id);
		$this->db->where('sps.status', true);
		return $this->db->get()->result();*/
		$sql = 'SELECT pc.id, pc.title, pc.type, pc.c_code FROM pro_course pc INNER JOIN pro_map_course pmc ON pmc.course_id=pc.id WHERE pc.id IN (SELECT course_id FROM stud_prog_course spc INNER JOIN stud_prog_state sps ON sps.sl=spc.sps_id WHERE sps.spc_id='.$spc_id.' AND sps.status=true AND sps.stud_id='.$userid.') AND pmc.program_id='.$pid;
		return $this->db->query($sql)->result();
	}
	
	public function getNoticesBYFid($userid)
	{
		$this->db->select('*')->from('program_notice');
		$this->db->where('userid', $userid);
		return $this->db->get()->result();
	}
	public function getNoticesBYid($id)
	{
		$this->db->select('*')->from('program_notice');
		$this->db->where('sl', $id);
		return $this->db->get()->result();
	}
	public function getAllNoticesByUid($userid)
	{
		$this->db->select('pn.*, prog.title as ptitle, pc.title as pc_title, ud.first_name, ud.last_name')->from('program_notice pn');
		$this->db->join('pro_users_role pur', 'pur.program_id=pn.program_sl', 'inner');
		$this->db->join('program prog', 'prog.id=pn.program_sl', 'left');
		$this->db->join('pro_course pc', 'pc.id=pn.course_sl', 'left');
		$this->db->join('user_details ud', 'ud.id=pn.userid', 'left');
		$this->db->where('pur.user_id', $userid);
		return $this->db->get()->result();
	}
	public function getStudentNotices($userid)
	{
		/*select pn.* from program_notice pn
		inner join pro_users_role pur on pur.program_id=pn.program_sl
		where pur.user_id=7;  , CONCAT(ud.fname," ",ud.lname) as profname*/
		$this->db->select('pn.*')->from('program_notice pn');
		$this->db->join('pro_users_role pur', 'pur.program_id=pn.program_sl', 'inner');
		//$this->db->join('user_details ud', 'ud.id=', 'left');
		$this->db->where('pur.user_id', $userid);
		return $this->db->get()->result();
	}
	
	public function getAllActiveAcaYear()
	{
		$this->db->where('status', true);
		return $this->db->get('acayear')->result();
	}
	
	public function insertTeacherNotice($data)
	{
		return $this->db->insert('program_notice', $data);
	}
	public function updateTeacherNotice($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('program_notice', $data);
	}
	public function deleteNoticeById($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('program_notice');
	}
	public function insertScheduleClass($data)
	{
		return $this->db->insert('schedule_class', $data);
	}
	
	public function getUserDetailsById($userid)
	{
		$this->db->where('id', $userid);
		return $this->db->get('user_details')->result();
	}
	public function updateUserAuth($data, $id)
	{
		$this->db->where('user_id', $id);
		return $this->db->update('user_auth', $data);
	}
	public function updateUserDetails($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('user_details', $data);
	}
	
	public function deleteUserSkills($id)
	{
		$this->db->where('user_id', $id);
		return $this->db->delete('b2c_user_skill');
	}
	public function deleteUserAcademic($id)
	{
		$this->db->where('user_id', $id);
		return $this->db->delete('b2c_user_academic');
	}
	
	public function getAllUserSkillsArray($userid)
	{
		$this->db->select('skills.id, name')->from('skills');
		$this->db->join('b2c_user_skill bus', 'bus.skill_id=skills.id', 'inner');
		$this->db->where('bus.user_id', $userid);
		return $this->db->get()->result();
	}
	public function getAllUserSkills($userid)
	{
		$this->db->select('name')->from('skills');
		$this->db->join('b2c_user_skill bus', 'bus.skill_id=skills.id', 'inner');
		$this->db->where('bus.user_id', $userid);
		return $this->db->get()->result();
	}
	public function getAllUserAcademic($userid)
	{
		$this->db->where('user_id', $userid);
		return $this->db->get('b2c_user_academic')->result();
	}
	
	public function insertProgram($data)
	{
		$this->db->insert('program', $data);
		return $this->db->insert_id();
	}
	public function insertUserRole($data)
	{
		return $this->db->insert('pro_users_role', $data);
	}
	public function checkAvailProgAdmission($pid)
	{
		$this->db->where('prog_id', $pid);
		return $this->db->get('prog_admission')->num_rows();
	}
	public function insertAdmission($data)
	{
		return $this->db->insert('prog_admission', $data);
	}
	public function updateAdmission($data, $pid)
	{
		$this->db->where('prog_id', $pid);
		return $this->db->update('prog_admission', $data);
	}
	public function getProgramById($id)
	{
		$this->db->select('prog.*, pa.*, pms.stream_id, pmo.org_id, acayear.yearnm');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->join('acayear', 'acayear.sl=pa.aca_year', 'inner');
		$this->db->join('pro_map_stream pms', 'pms.program_id=prog.id', 'inner');
		$this->db->join('pro_map_org pmo', 'pmo.program_id=prog.id', 'inner');
		$this->db->where('prog.id', $id);
		return $this->db->get('program prog')->result();
	}
	public function getAdmissionInfoByPid($id)
	{
		$this->db->where('prog_id', $id);
		return $this->db->get('prog_admission')->result();
	}
	public function updateProgram($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('program', $data);
	}
	public function checkAvailableSemester($pid)
	{
		if($pid!=0){
			$this->db->where('program_id', $pid);
			return $this->db->get('pro_map_sem')->num_rows();
		}else{
			return 0;
		}
	}
	public function removeSemestersByProgId($prog_id)
	{
		$query = 'DELETE FROM pro_semister WHERE id IN (SELECT sem_id FROM pro_map_sem WHERE program_id = '.$prog_id.')';
		return $this->db->query($query);
	}
	public function getProgRoleRequest($role, $prog_id, $userid)
	{
		$this->db->where('role', $role);
		$this->db->where('program_id', $prog_id);
		$this->db->where('status', 'pending');
		$this->db->where_not_in('user_id', $userid);
		$this->db->order_by('add_date', 'DESC');
		return $this->db->get('pro_users_role')->num_rows();
	}
	public function getAllRequestByIdRole($prog, $role, $userId, $status)
	{
		
		$this->db->select("pur.id AS purid, ud.id, CONCAT(ud.first_name,' ',ud.last_name) AS name, ud.email, ud.phone, ud.photo_sm, ud.about_me, ud.organization, ud.designation, prog.title, pur.role, pur.status")->from('user_details ud');
		$this->db->join('pro_users_role pur', 'pur.user_id=ud.id', 'inner');
		$this->db->join('program prog', 'prog.id=pur.program_id', 'inner');
		if($prog!='all'){
			$this->db->where('pur.program_id', $prog);
		}
		if($role!='All'){
			$this->db->where('pur.role', $role);
		}
		if($userId!=null){
			$this->db->where('prog.user_id', $userId);
		}
		$this->db->where('pur.status', $status);
		$this->db->where('prog.status', 'approved');
		$this->db->order_by('pur.add_date', 'DESC');
		return $this->db->get()->result();
	}
	
	public function getMyRequestForPrograms($userid)
	{
		$this->db->select("title, code, type, category, 'Approval' as role, status")->from('program');
		$this->db->where('user_id', $userid);
		$query1 = $this->db->get_compiled_select();
		
		$this->db->select("program.title, program.code, program.type, program.category, pur.role, pur.status")->from('program');
		$this->db->join('pro_users_role pur', 'pur.program_id=program.id', 'inner');
		$this->db->where('pur.user_id', $userid);
		$this->db->where('program.user_id<>', $userid);
		$query2 = $this->db->get_compiled_select();
		
		return $this->db->query($query1." UNION ".$query2)->result();
	}
	public function getProgOrganizations($pid)
	{
		$this->db->select('po.*')->from('pro_organization po');
		$this->db->join('pro_map_org pmo', 'pmo.org_id=po.id', 'inner');
		$this->db->where('pmo.program_id', $pid);
		$this->db->order_by('po.title', 'ASC');
		return $this->db->get()->result();
	}
	public function checkRedundLearningApply($userid, $pid)
	{
		$this->db->where('cand_id', $userid);
		$this->db->where('prog_id', $pid);
		return $this->db->get('adm_can_apply')->num_rows();
	}
	public function checkReduntAdmission($prog_id, $userid)
	{
		$this->db->where('prog_id', $prog_id);
		$this->db->where('cand_id', $userid);
		return $this->db->get('adm_can_apply')->num_rows();
	}
	public function getAllProgramsNotUser($role, $userid)
	{
		$sql = "SELECT prog.*, pa.* 
				FROM program prog 
				LEFT JOIN prog_admission pa ON pa.prog_id=prog.id
				WHERE id NOT IN (SELECT program_id FROM pro_users_role WHERE user_id=".$userid." AND role='".$role."') 
				AND status='approved'
				ORDER BY date_added DESC;";
		return $this->db->query($sql)->result();
	}
	public function getUserApplication($email)
	{
		$this->db->select('pui.*, prog.title')->from('pro_user_invite pui');
		$this->db->join('program prog', 'prog.id=pui.program_id', 'inner');
		$this->db->where('pui.user_email', $email);
		$this->db->where('pui.itype', 'applied');
		$this->db->where('pui.status', 'pending');
		return $this->db->get()->result();
	}
	public function getAllMyInvitationRespondById($userid)
	{
		$this->db->select("pui.*, prog.title as prog_name, CONCAT(ud.first_name,' ',ud.last_name) as hostname")->from('pro_user_invite pui');
		$this->db->join('program prog', 'prog.id=pui.program_id', 'inner');
		$this->db->join('user_details ud', 'ud.email=pui.user_email', 'inner');
		$this->db->where('pui.invite_by', $userid);
		$this->db->order_by('invite_datetime', 'DESC');
		return $this->db->get()->result();
	}
	public function getUserInvitationsByEmail($email)
	{
		$this->db->select("pui.*, prog.title as prog_name, CONCAT(ud.first_name,' ',ud.last_name) as hostname")->from('pro_user_invite pui');
		$this->db->join('program prog', 'prog.id=pui.program_id', 'inner');
		$this->db->join('user_details ud', 'ud.id=pui.invite_by', 'inner');
		$this->db->where('pui.user_email', $email);
		$this->db->order_by('invite_datetime', 'DESC');
		return $this->db->get()->result();
	}
	public function checkUserRoleOnProgram($pid, $role, $userid)
	{
		$this->db->where('role', $role);
		$this->db->where('program_id', $pid);
		$this->db->where('user_id', $userid);
		return $this->db->get('pro_users_role')->num_rows();
	}
	public function updateUserInvite($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('pro_user_invite', $data);
	}
	
	public function checkRedundantADM($prog_id, $userid)
	{
		$this->db->where('prog_id', $prog_id);
		$this->db->where('cand_id', $userid);
		return $this->db->get()->num_rows();
	}
	
	public function getCurAdmissionProg($userid)
	{
		$curdate = date('Y-m-d');
		$this->db->select('prog.*, pa.*')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'inner');
		$this->db->where('prog.type', '1');
		$this->db->where('prog.user_id', $userid);
		$this->db->where('pa.astart_date<=', $curdate);
		$this->db->where('pa.aend_date>=', $curdate);
		return $this->db->get()->result();
	}
	public function getCurLearningProg($userid)
	{
		$this->db->select('prog.*, pa.*')->from('program prog');
		$this->db->join('prog_admission pa', 'pa.prog_id=prog.id', 'left');
		$this->db->where('prog.type', '2');
		$this->db->where('prog.user_id', $userid);
		return $this->db->get()->result();
	}
	
	/********************************************************************/
	
	public function getProgramOrg($prog_id)
	{
		$this->db->select('org.*')->from('pro_organization org');
		$this->db->join('pro_map_org pmo', 'pmo.org_id=org.id', 'inner');
		$this->db->where('pmo.program_id', $prog_id);
		$this->db->order_by('org.add_date', 'DESC');
		return $this->db->get()->result();
	}
	public function getOrgByid($org_id)
	{
		$this->db->where('id', $org_id);
		return $this->db->get('pro_organization')->result_array();
	}
	public function insertProOrg($data)
	{
		$this->db->insert('pro_organization', $data);
		return $this->db->insert_id();
	}
	public function updateProOrg($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('pro_organization', $data);
	}
	public function deleteProOrg($org_id)
	{
		$this->db->where('id', $org_id);
		return $this->db->delete('pro_organization');
	}
	public function deleteProMapOrg($org_id)
	{
		$this->db->where('org_id', $org_id);
		return $this->db->delete('pro_map_org');
	}
	public function insertProMapOrg($data)
	{
		return $this->db->insert('pro_map_org', $data);
	}
	
	/********************************************************************/
	
	public function getProgramStreams($prog_id)
	{
		$this->db->select('strm.*, org.title as institute')->from('pro_stream strm');
		$this->db->join('pro_organization org', 'org.id=strm.org_id', 'inner');
		$this->db->join('pro_map_stream pms', 'pms.stream_id=strm.id', 'inner');
		$this->db->where('pms.program_id', $prog_id);
		$this->db->order_by('strm.add_date', 'DESC');
		return $this->db->get()->result();
	}
	public function getStreamByid($strm_id)
	{
		$this->db->where('id', $strm_id);
		return $this->db->get('pro_stream')->result_array();
	}
	public function insertProStream($data)
	{
		$this->db->insert('pro_stream', $data);
		return $this->db->insert_id();
	}
	public function insertProMapStream($data)
	{
		return $this->db->insert('pro_map_stream', $data);
	}
	public function updateProStream($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('pro_stream', $data);
	}
	public function deleteProStrm($strm_id)
	{
		$this->db->where('id', $strm_id);
		return $this->db->delete('pro_stream');
	}
	public function deleteProMapStrm($strm_id)
	{
		$this->db->where('stream_id', $strm_id);
		return $this->db->delete('pro_map_stream');
	}
	
	public function insertUserInvite($data)
	{
		return $this->db->insert('pro_user_invite', $data);
	}
	
	public function checkValidInvite($program_id, $user_email, $role)
	{
		$this->db->where('program_id', $program_id);
		$this->db->where('user_email', $user_email);
		$this->db->where('role', $role);
		return $this->db->get('pro_user_invite')->result();
	}
	public function getUserListNotInProgram($prog_id, $utype)
	{
		$sql = "SELECT
					first_name, last_name, email, phone 
				FROM user_auth
				WHERE user_id NOT IN (SELECT user_id FROM pro_users_role WHERE program_id=".$prog_id." AND role = '".trim($utype)."') 
				AND user_type='".trim(strtolower($utype))."'";
		return $this->db->query($sql)->result();
	}
	
	public function getUserAcademic($uid)
	{
		$this->db->select('bua.*, dg.short')->from('b2c_user_academic bua');
		$this->db->join('degree dg', 'dg.id=bua.degree_id', 'left outer');
		$this->db->where('user_id', $uid);
		return $this->db->get()->result();
	}
	
	public function getStudAdmissionListBYAF($pid)
	{
		$this->db->select("acp.*, CONCAT(ud.first_name,' ',ud.last_name) as stud_name, ud.email, ud.phone, ud.photo_sm, ud.verification_status")->from('adm_can_apply acp');
		$this->db->join('user_auth ud', 'ud.user_id=acp.cand_id', 'inner');
		$this->db->where('acp.prog_id', $pid);
		return $this->db->get()->result();
	}
	public function getUserEnrollRoll($uid, $pid)
	{
		$this->db->select('spc.sl as spc_id, spc.enrollment_no, sps.roll_no, ps.title')->from('stud_prog_cons spc');
		$this->db->join('stud_prog_state sps', 'sps.spc_id=spc.sl', 'inner');
		$this->db->join('pro_semister ps', 'ps.id=sps.sem_id', 'inner');
		$this->db->where('spc.prog_id', $pid);
		$this->db->where('spc.stud_id', $uid);
		$this->db->where('sps.status', true);
		$this->db->order_by('sps.sem_id', 'ASC');
		return $this->db->get()->result();
	}
	public function getUsersArrayByACPIds($acp_ids)
	{
		$this->db->select("ud.email, CONCAT(ud.first_name,' ',ud.last_name) as name")->from('user_details ud');
		$this->db->join('adm_can_apply acp', 'acp.cand_id=ud.id', 'inner');
		$this->db->where_in('acp.sl', $acp_ids);
		return $this->db->get()->result_array();
	}
	public function getUserIdsByACPIds($acp_ids)
	{
		$this->db->select("ud.id")->from('user_details ud');
		$this->db->join('adm_can_apply acp', 'acp.cand_id=ud.id', 'inner');
		$this->db->where_in('acp.sl', $acp_ids);
		return $this->db->get()->result();
	}
	public function getCourseIdsBySemid($semid, $pid)
	{
		$this->db->select('course_id')->from('pro_map_course');
		$this->db->where('program_id', $pid);
		$this->db->where('sem_id', $semid);
		$this->db->order_by('course_id', 'ASC');
		return $this->db->get()->result();
	}
	public function getCourseIdsByProgid($pid, $sem_id)
	{
		$this->db->select('course_id')->from('pro_map_course');
		$this->db->where('program_id', $pid);
		$this->db->where('sem_id', $sem_id);
		$this->db->order_by('course_id', 'ASC');
		return $this->db->get()->result();
	}
	
	public function getAllRoleStatusByIdRole($userid)
	{
		$this->db->select('pur.role, pur.status, prog.title')->from('pro_users_role pur');
		$this->db->join('program prog', 'prog.id=pur.program_id', 'inner');
		$this->db->where('pur.user_id', $userid);
		$this->db->where('prog.status', 'approved');
		$this->db->where_not_in('pur.role', 'Teacher');
		$this->db->order_by('pur.add_date', 'DESC');
		return $this->db->get()->result();
	}
	
	public function getUserApplyProgStatus($userid)
	{
		$this->db->select('acp.approve_flag, acp.prog_id, prog.title, prog.feetype, prog.email, prog.mobile, prog.duration, pa.sem_type')->from('adm_can_apply acp');
		$this->db->join('program prog', 'prog.id=acp.prog_id', 'inner');
		$this->db->join('prog_admission pa', 'pa.prog_id=acp.prog_id', 'inner');
		//$this->db->join('stud_prog_cons spc', 'spc.prog_id=acp.prog_id', 'left outer');
		$this->db->where('acp.cand_id', $userid);
		return $this->db->get()->result();
	}
	public function getUserSPCData($userid, $pid)
	{
		$this->db->where('prog_id', $pid);
		$this->db->where('stud_id', $userid);
		return $this->db->get('stud_prog_cons')->result();
	}
	
	public function getProgramCourse($cid)
	{
		$this->db->select('pc.id as cid, pc.title as course_title, pc.c_code as course_code, prog.id as pid, prog.title as program_title, prog.code as program_code')->from('pro_course pc');
		$this->db->join('program prog', 'prog.id=pc.prog_id', 'inner');
		$this->db->where('pc.id', $cid);
		$this->db->where('prog.status', 'approved');
		return $this->db->get()->result();
	}
	
	public function updateMultiAdmApply($acp_ids, $val)
	{
		$this->db->set('approve_flag', $val);
		$this->db->set('change_flag_date', date('Y-m-d H:i:s'));
		$this->db->where_in('sl', $acp_ids);
		return $this->db->update('adm_can_apply');
	}
	public function getTotalProgByUserRole($role, $userid, $pid)
	{
		$this->db->join('pro_users_role pur', 'pur.program_id=p.id', 'inner');
		$this->db->where('pur.role', $role);
		if($userid!=null){
			$this->db->where('pur.user_id', $userid);
		}
		if($pid!=null){
			$this->db->where('pur.program_id', $pid);
		}
		$this->db->where('pur.status', 'accepted');
		$this->db->where('p.status', 'approved');
		return $this->db->get('program p')->num_rows();
	}
	public function getStudentNameEmailByProgId($prog)
	{
		$this->db->select("ud.email, CONCAT(ud.first_name,' ',ud.last_name) as name")->from('user_details ud');
		$this->db->join('pro_users_role pur', 'pur.user_id=ud.id', 'inner');
		$this->db->where('pur.program_id', $prog);
		$this->db->where('pur.role', 'Student');
		$this->db->where('pur.status', 'accepted');
		return $this->db->get()->result_array();
	}
	public function getStudentNameEmailById($id)
	{
		$this->db->select("email, CONCAT(first_name,' ',last_name) as name")->from('user_details');
		$this->db->where_in('id', $id);
		return $this->db->get()->result_array();
	}
	public function getProgramCourseNameByProgId($prog, $cid)
	{
		$this->db->select('program.title as program_title, pro_course.title as course_title')->from('program');
		$this->db->join('pro_course', 'pro_course.prog_id=program.id', 'inner');
		$this->db->where('program.id', $prog);
		$this->db->where('pro_course.id', $cid);
		$this->db->where('prog.status', 'approved');
		return $this->db->get()->result();
	}
	/***********************************LIVE-CLASS**********************************/
	public function insertLiveClass($data)
	{
		return $this->db->insert('live_class',$data);
	}
	
	public function updateLiveClass($cid, $data){
		$this->db->where('course_id', $cid);
		return $this->db->update('live_class', $data);
	}

	public function getLiveClassByCid($cid){
		$this->db->where('course_id', $cid);
		$this->db->where('active', true);
		return $this->db->get('live_class')->result();
	}

	public function insertLiveClassInvitation($data)
	{
		return $this->db->insert('live_class_students',$data);
	}

	public function getAllLiveClassInvitationByLiveId($liveId){
		$this->db->select('lcs.*, ua.email')->from('live_class_students lcs');
		$this->db->join('user_auth ua', 'ua.user_id=lcs.student_id', 'inner');
		$this->db->where('lcs.live_class_id', $liveId);
		$this->db->where('ua.user_type', 'student');
		return $this->db->get()->result();
	}

	public function getMyInvitation($userId, $cid){
		$this->db->select('lcs.*, lc.room_name')->from('live_class_students lcs');
		$this->db->join('live_class lc', 'lc.id=lcs.live_class_id', 'inner');
		$this->db->where('lc.course_id', $cid);
		$this->db->where('lc.active',true);
		$this->db->where('lcs.student_id',$userId);
		return $this->db->get()->result();
	}

	public function getAllMyInvitation($userId){
		$this->db->select('lcs.*, lc.room_name, pc.c_code')->from('live_class_students lcs');
		$this->db->join('live_class lc', 'lc.id=lcs.live_class_id', 'inner');
		$this->db->join('pro_course pc', 'pc.id=lc.course_id', 'inner');
		$this->db->where('lc.active',true);
		$this->db->where('lcs.student_id',$userId);
		return $this->db->get()->result();
	}

	public function joinMeeting($lc_id, $userId, $data)
	{
		$this->db->where('live_class_id', $lc_id);
		$this->db->where('student_id', $userId);
		return $this->db->update('live_class_students', $data);
	}

	public function takeAttendance($data)
	{
		$this->db->where('prog_id', $data['prog_id']);
		$this->db->where('course_id', $data['course_id']);
		$this->db->where('student_id', $data['student_id']);
		$this->db->where('teacher_id', $data['teacher_id']);
		$this->db->where('live_id', $data['live_id']);

		$q = $this->db->get('pro_attendance');
		if($q->num_rows() > 0){
			$result = $q->result();
			$id = $result[0]->id;
			$this->db->reset_query();
			$this->db->where('id', $id);
			$data1['m_datetime'] =  date('Y-m-d H:i:s');
			return $this->db->update('pro_attendance', $data1);
		}else{
			$this->db->reset_query();
			return $this->db->insert('pro_attendance', $data);
		}
	}

	public function getLiveClasses($userid, $from, $to){
		$this->db->where('teacher_id', $userid);
		$this->db->where('DATE(start_time) >=', $from);
		$this->db->where('DATE(start_time) <=', $to);
		$this->db->order_by('start_time', 'DESC');
		return $this->db->get('live_class')->result();
	}


	public function getAttendance($live_id)
	{
		$this->db->select("CONCAT(ud.first_name,' ',ud.last_name) as name, pa.status, lc.start_time, lcs.start_time as join_time")->from('pro_attendance pa');
		$this->db->join('user_details ud', 'ud.id=pa.student_id');
		$this->db->join('live_class lc', 'lc.id=pa.live_id');
		$this->db->join('live_class_students lcs', 'lcs.live_class_id=pa.live_id and lcs.student_id=pa.student_id');
		$this->db->where('pa.live_id', $live_id);
		return $this->db->get()->result();
	}
	
	public function getStudEmailNameById($student)
	{
		$this->db->select("email, CONCAT(first_name,' ',last_name) as name")->from('user_auth');
		$this->db->where('user_type', 'student');
		$this->db->where_in('user_id', $student);
		return $this->db->get()->result_array();
	}

	/************************************************************************************************************ */

	public function getSingleStudent($id){
		$sql = "SELECT ud.id as stud_id, CONCAT(ud.first_name,' ',ud.last_name) as name, ud.photo_sm, ud.phone, ud.email, spc.enrollment_no, spc.totalfees, spc.discount,spc.payment_done,spc.payment_status, sps.percent as sps_percent,sps.cgpa as sps_cgpa, spc.status, sps.sl as sps_id, sps.percent, spc.certificate, spc.marksheet, spc.sl as spc_id FROM user_details ud INNER JOIN stud_prog_cons spc ON spc.stud_id=ud.id LEFT JOIN stud_prog_state sps ON sps.spc_id=spc.sl WHERE ud.id =".$id;
		return $this->db->query($sql)->row();
	}

	public function testDetailsById($id){
		$this->db->select('tt.id as tt_id,tt.title,prog.title as prog_title,tpub.publish_type,rc.test_start_dttm,rc.test_end_dttm')->from('test tt');
		$this->db->join('program prog','prog.id=tt.cat_id');
		$this->db->join('test_pub tpub','tpub.test_id=tt.id');
		$this->db->join('runtest_can rc','rc.test_pub_id=tpub.id','left');
		$this->db->where('tt.id',$id);
		return $this->db->get()->row();
	}

	public function getSectionById($test_id){
		$this->db->select('section.id, section.test_id, section.section_name, section.type_id, section.ques_number')->from('section');
		$this->db->where('section.test_id',$test_id);
		return $this->db->get()->result();
	}

	public function allQuestionsOfTest($user_id, $test_pub_id){
		$this->db->select('ques.*,rt.sec_id,rt.ans_body,tt.type_name')->from('questions ques');
		$this->db->join('run_test rt','rt.ques_id=ques.id');
		$this->db->join('runtest_can rtc','rtc.id=rt.runtest_can_id');
		$this->db->join('test_type tt','tt.type_id=ques.type_id');
		$this->db->where('rtc.can_id',$user_id);
		$this->db->where('rtc.test_pub_id',$test_pub_id);
		return $this->db->get()->result();
	}

	public function getCorrectAnswer(){
		$this->db->select('id,body,ques_id,correct_flag')->from('options');
		$this->db->where('correct_flag',true);
		return $this->db->get()->result();
	}

	public function getGivenAnswer(){
		$this->db->select('options.id,options.body,options.ques_id,options.correct_flag')->from('options');
		$this->db->join('run_test_option rtp','rtp.option_id=options.id');
		return $this->db->get()->result();		
	}

	public function getQuestionBySection($id){
		$this->db->select('ques_id')->from('section_question_map sqm');
		$this->db->where('section_id',$id);
		return $this->db->get()->result();
	}

	public function getQuestionBankBySection($id){
		$this->db->select('qb_id')->from('section_qb_map sqbm');
		$this->db->where('sec_id',$id);
		return $this->db->get()->result();
	}

	public function newQuestionAddOfSection($data){
		return $this->db->insert_batch('section_question_map', $data);
	}

	public function updateQuestionOfSection($data,$section){
		$query = 'DELETE FROM section_question_map WHERE ques_id IN ('.$data.') AND section_id='.$section;
		return $this->db->query($query);
	}

	public function newQuestionBankAddOfSection($data){
		return $this->db->insert_batch('section_qb_map', $data);
	}

	public function updateQuestionBankOfSection($data,$section){
		$query = 'DELETE FROM section_qb_map WHERE qb_id IN ('.$data.') AND sec_id='.$section;
		return $this->db->query($query);
	}

}