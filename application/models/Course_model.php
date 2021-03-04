<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model
{
	public function getAllSemestersByProgId($prog_id)
	{
		$this->db->select('ps.*')->from('pro_semister ps');
		$this->db->join('pro_map_sem pms', 'pms.sem_id=ps.id', 'inner');
		$this->db->where('pms.program_id', $prog_id);
		$this->db->order_by('ps.title', 'ASC');
		return $this->db->get()->result();
	}
	public function checkAvailPMC($pid, $cid)
	{
		$this->db->where('course_id', $cid);
		$this->db->where('program_id', $pid);
		return $this->db->get('pro_map_course')->num_rows();
	}
	public function getProgramCourses($prog_id)
	{
		$this->db->where('prog_id', $prog_id);
		$this->db->order_by('title', 'ASC');
		return $this->db->get('pro_course')->result();
	}
	public function insertProCourse($data)
	{
		$this->db->insert('pro_course', $data);
		return $this->db->insert_id();
	}
	public function updateProCourse($data, $id)
	{
		$this->db->where('id', $id);
		return $this->db->update('pro_course', $data);
	}
	public function insertProMapCource($data)
	{
		return $this->db->insert('pro_map_course', $data);
	}
	public function updateProMapCource($data, $cid, $pid)
	{
		$this->db->where('course_id', $cid);
		$this->db->where('program_id', $pid);
		return $this->db->update('pro_map_course', $data);
	}
	public function getCourseDetailsById($cid)
	{
		$this->db->select('pc.*, pmc.sem_id');
		$this->db->join('pro_map_course pmc', 'pmc.course_id=pc.id', 'inner');
		$this->db->where('pc.id', $cid);
		return $this->db->get('pro_course pc')->result();
	}
	public function deleteCourse($cid)
	{
		$this->db->where('id', $cid);
		return $this->db->delete('pro_course');
	}
	public function getCourseByCode($ccode)
	{
		$this->db->where('c_code', $ccode);
		return $this->db->get('pro_course')->result();
	}
	public function getScheduleClassByCid($cid, $userid)
	{
		$this->db->where('course_sl', $cid);
		$this->db->where('user_id', $userid);
		$this->db->order_by('start_datetime', 'ASC');
		return $this->db->get('schedule_class')->result();
	}
	public function getScheduleClassById($cid)
	{
		$this->db->select("sc.*, CONCAT(ud.first_name,' ',ud.last_name) as facname");
		$this->db->join('user_details ud', 'ud.id=sc.user_id', 'inner');
		$this->db->where('sc.course_sl', $cid);
		$this->db->order_by('sc.start_datetime', 'ASC');
		return $this->db->get('schedule_class sc')->result();
	}
	
	public function getStudentDoubtsById($cid, $userid)
	{
		$this->db->select("pd.*, CONCAT(uto.first_name,' ',uto.last_name) as toname, CONCAT(ufro.first_name,' ',ufro.last_name) as fromname");
		$this->db->from('program_doubts pd');
		$this->db->join('user_details uto', 'uto.id=pd.fac_sl', 'left outer');
		$this->db->join('user_details ufro', 'ufro.id=pd.ans_by', 'left outer');
		$this->db->where('pd.course_sl', $cid);
		$this->db->where('pd.stud_sl', $userid);
		$this->db->order_by('pd.tdatetime', 'DESC');
		return $this->db->get()->result();
	}
	public function getStudentDoubtsByCid($cid, $userid)
	{
		$user = array(0, $userid);
		$this->db->select("pd.*, CONCAT(std.first_name,' ',std.last_name) as studname, CONCAT(uto.first_name,' ',uto.last_name) as toname, CONCAT(ufro.first_name,' ',ufro.last_name) as fromname");
		$this->db->from('program_doubts pd');
		$this->db->join('user_details std', 'std.id=pd.stud_sl', 'inner');
		$this->db->join('user_details uto', 'uto.id=pd.fac_sl', 'left outer');
		$this->db->join('user_details ufro', 'ufro.id=pd.ans_by', 'left outer');
		$this->db->where('pd.course_sl', $cid);
		$this->db->where_in('pd.fac_sl', $user);
		$this->db->order_by('pd.tdatetime', 'DESC');
		return $this->db->get()->result();
	}
	
	public function insertStudentDoubts($data)
	{
		return $this->db->insert('program_doubts', $data);
	}
	public function updateStudentDoubts($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('program_doubts', $data);
	}
/*-------------------------------------------------------*/
	public function getLecturessByCid($cid)
	{
		$this->db->select("pl.*, CONCAT(ud.first_name, ' ', ud.last_name) as user_name");
		$this->db->join('user_details ud', 'ud.id=pl.user_id', 'inner');
		$this->db->where('pl.course_sl', $cid);
		$this->db->where('pl.archive', false);
		$this->db->order_by('pl.add_date', 'DESC');
		return $this->db->get('pro_lectures pl')->result();
	}
	public function getCLectureByID($id)
	{
		$this->db->where('sl', $id);
		return $this->db->get('pro_lectures')->result_array();
	}
	public function getLectureFile($id)
	{
		$this->db->select('file_name');
		$this->db->where('sl', $id);
		return $this->db->get('pro_lectures')->result();
	}
	public function insertLecDLrecord($data)
	{
		return $this->db->insert('pro_lecture_download', $data);
	}
	public function insertClecture($data)
	{
		return $this->db->insert('pro_lectures', $data);
	}
	public function updateClecture($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('pro_lectures', $data);
	}
	public function delLectureById($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('pro_lectures');
	}

/*-------------------------------------------------------*/
	public function getResourcesByCid($cid)
	{
		$this->db->select("pr.*, CONCAT(ud.first_name, ' ', ud.last_name) as user_name");
		$this->db->join('user_details ud', 'ud.id=pr.user_id', 'inner');
		$this->db->where('pr.course_sl', $cid);
		$this->db->where('pr.archive', false);
		$this->db->order_by('pr.add_date', 'DESC');
		return $this->db->get('pro_resources pr')->result();
	}
	public function getCResourceFileByID($id)
	{
		$this->db->where('sl', $id);
		return $this->db->get('pro_res_fileslinks')->result();
	}
	public function getCResourceFileByRID($id)
	{
		$this->db->where('res_sl', $id);
		return $this->db->get('pro_res_fileslinks')->result();
	}
	public function insertResDLrecord($data)
	{
		return $this->db->insert('pro_resource_download', $data);
	}
	public function insertCresource($data)
	{
		return $this->db->insert('pro_resources', $data);
	}
	public function updateCresource($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('pro_resources', $data);
	}
	public function getCResourceByID($id)
	{
		$this->db->where('sl', $id);
		return $this->db->get('pro_resources')->result_array();
	}
	public function getResourceFilesById($id)
	{
		$this->db->where('res_sl', $id);
		return $this->db->get('pro_res_fileslinks')->result();
	}
	public function insertCRFiles($tableName, $data)
	{
		return $this->db->insert($tableName, $data);
	}
	public function delResourceById($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('pro_resources');
	}
	public function delResourceFilesByID($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('pro_res_fileslinks');
	}
	
/*-------------------------------------------------------*/
	public function insertCWeightage($data)
	{
		return $this->db->insert('pro_course_wt', $data);
	}
	public function updateCWeightage($data, $assgnid)
	{
		$this->db->where('serial', $assgnid);
		return $this->db->update('pro_course_wt', $data);
	}

	public function getAssignmentByCid($id)
	{
		$this->db->select("pa.*, CONCAT(ud.first_name, ' ', ud.last_name) as user_name, pcw.full_marks as marks");
		$this->db->join('user_details ud', 'ud.id=pa.user_id', 'inner');
		$this->db->join('pro_course_wt pcw', 'pcw.serial=pa.sl', 'inner');
		$this->db->where('pa.course_sl', $id);
		$this->db->where('pa.archive', false);
		$this->db->order_by('pa.add_date', 'DESC');
		return $this->db->get('pro_assignments pa')->result();
	}
	public function getCAssignmentByID($id)
	{
		$this->db->select('pa.*, pcw.full_marks as marks');
		$this->db->join('pro_course_wt pcw', 'pcw.serial=pa.sl', 'inner');
		$this->db->where('pa.sl', $id);
		return $this->db->get('pro_assignments pa')->result();
	}
	public function getAssignmentFilesById($id)
	{
		$this->db->where('assgn_sl', $id);
		return $this->db->get('pro_assgn_files')->result();
	}
	public function insertCAssignment($data)
	{
		$this->db->insert('pro_assignments', $data);
		return $this->db->insert_id();
	}
	public function updateCAssignment($data, $id)
	{
		$this->db->where('sl', $id);
		return $this->db->update('pro_assignments', $data);
	}
	public function delAssignmentById($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('pro_assignments');
	}
	public function delAssignmentFilesByID($id)
	{
		$this->db->where('sl', $id);
		return $this->db->delete('pro_assgn_files');
	}
	public function getAssignmentSubmission($id, $userid)
	{
		$this->db->select('pro_ass_submit.*, pro_assignments.deadline');
		$this->db->join('pro_assignments', 'pro_assignments.sl=pro_ass_submit.ass_sl', 'inner');
		$this->db->where('ass_sl', $id);
		$this->db->where('user_sl', $userid);
		return $this->db->get('pro_ass_submit')->result();
	}
	public function getStudentAssignmentSubmission($id)
	{
		/*$this->db->select('pas.*, ud.first_name, ud.last_name, psm.marks')->from('pro_ass_submit pas');
		$this->db->join('user_details ud', 'ud.id=pas.user_sl', 'inner');
		$this->db->join('pro_stud_marks psm', 'psm.stud_sl=pas.user_sl', 'inner');
		$this->db->where('pas.ass_sl', $id);
		return $this->db->get()->result();*/
		$this->db->select('pas.*, ud.first_name, ud.last_name, pcw.sl as pcw_sl, pcw.full_marks, psm.marks')->from('pro_ass_submit pas');
		$this->db->join('user_details ud', 'ud.id=pas.user_sl', 'inner');
		$this->db->join('pro_course_wt pcw', 'pcw.serial=pas.ass_sl', 'inner');
		$this->db->join('pro_stud_marks psm', 'psm.pro_course_wt_sl=pcw.sl', 'left');
		$this->db->where('pas.ass_sl', $id);
		return $this->db->get()->result();
	}
	public function getAllAssignmentMarksByCid($cid, $stud_id)
	{
		$this->db->select('pcw.sl as pcw_sl, pcw.type, pcw.subject, pcw.weightage, pcw.full_marks, psm.marks')->from('pro_course_wt pcw');
		$this->db->join('pro_stud_marks psm', 'psm.pro_course_wt_sl=pcw.sl', 'left outer');
		$this->db->where('pcw.course_sl', $cid);
		$this->db->where('psm.stud_sl', $stud_id);
		return $this->db->get()->result();
	}
	public function getOnlySubjectNameByCid($cid)
	{
		$this->db->where('course_sl', $cid);
		return $this->db->get('pro_course_wt')->result();
	}
	public function getAssignSubFiles($id)
	{
		$this->db->where('ass_sub_sl', $id);
		return $this->db->get('pro_ass_sub_files')->result();
	}
	public function insertStudAssignment($data)
	{
		return $this->db->insert('pro_ass_submit', $data);
	}
	public function insertStudentAssgnMark($data)
	{
		return $this->db->insert('pro_stud_marks', $data);
	}
}

?>