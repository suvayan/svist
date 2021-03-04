<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class Exam_model extends CI_Model {
	
	public function insertDataRetId($tablename, $data)
	{
		$this->db->insert($tablename, $data);
		return $this->db->insert_id();
	}
	public function insertData($tablename, $data)
	{
		return $this->db->insert($tablename, $data);
	}
	public function updateData($tablename, $data, $where)
	{
		$this->db->where($where);
		return $this->db->update($tablename, $data);
	}
	public function deleteData($tablename, $where)
	{
		$this->db->where($where);
		return $this->db->delete($tablename);
	}
	
	public function getAllTestTypes()
	{
		$this->db->where('parent_id', 1);
		$this->db->order_by('type_name', 'ASC');
		return $this->db->get('test_type')->result();
	}
	
	public function getAllUserQuestionBanks($userid)
	{
		$this->db->select('qb.*, prog.title as prog_title, pc.title as pc_title, tt.type_name')->from('question_bank qb');
		$this->db->join('program prog', 'prog.id=qb.cat_id', 'inner');
		$this->db->join('test_type tt', 'tt.id=qb.type_id', 'inner');
		$this->db->join('pro_course pc', 'pc.id=qb.scat_id', 'left');
		$this->db->where('qb.user_id', $userid);
		$this->db->where('qb.archive_status', false);
		$this->db->order_by('create_date_time', 'DESC');
		return $this->db->get()->result();
	}
	public function getTotalQuestionUnderQBs($qid, $userid)
	{
		$this->db->where('ques_id', $qid);
		$this->db->where('user_id', $userid);
		$this->db->where('status', true);
		return $this->db->get('questions_qb_map')->num_rows();
	}
	public function getQuestionBankById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get('question_bank')->result();
	}
	public function getAllOptionsByQid($id)
	{
		$this->db->where('ques_id', $id);
		return $this->db->get('options')->result();
	}
	
	public function getQuestionById($id, $userid)
	{
		$this->db->select('ques.*, qqm.qb_id')->from('questions ques');
		$this->db->join('questions_qb_map qqm', 'qqm.ques_id=ques.id', 'inner');
		$this->db->where('ques.id', $id);
		$this->db->where('ques.user_id', $userid);
		return $this->db->get()->result();
	}
	public function getAllUserQuestions($qb_id, $userid)
	{
		$this->db->select('ques.*, tt.type_name, qqm.qb_id')->from('questions ques');
		$this->db->join('test_type tt', 'tt.id=ques.type_id', 'inner');
		$this->db->join('questions_qb_map qqm', 'qqm.ques_id=ques.id', 'inner');
		$this->db->join('question_bank qb', 'qb.id=qqm.qb_id', 'inner');
		if($qb_id!=""){
			$this->db->where('qqm.qb_id', $qb_id);
		}
		
		$this->db->where('ques.user_id', $userid);
		$this->db->where('ques.archive_status', false);
		$this->db->where('qb.archive_status', false);
		$this->db->order_by('ques.create_date_time', 'DESC');
		return $this->db->get()->result();
	}
	public function getAllSelectedUserQuestions($qbid, $userid, $sid)
	{
		$sql = 'SELECT 
					ques.*, tt.type_name, qqm.qb_id, sqm.ques_id 
				FROM questions ques 
				INNER JOIN test_type tt ON tt.id=ques.type_id 
				INNER JOIN questions_qb_map qqm ON qqm.ques_id=ques.id 
				INNER JOIN question_bank qb ON qb.id=qqm.qb_id 
				LEFT JOIN section_question_map sqm ON sqm.ques_id=ques.id
				WHERE qqm.qb_id='.$qbid.' 
				AND ques.user_id='.$userid.' 
				AND ques.archive_status=FALSE 
				AND qb.archive_status=FALSE 
				ORDER BY ques.create_date_time DESC';
		return $this->db->query($sql)->result();
	}
	
	public function getAllUserTests($userid, $pid, $cid)
	{
		$this->db->select('*')->from('test');
		$this->db->where('archive_status', false);
		$this->db->where('user_id', $userid);
		if($pid!=''){
			$this->db->where('cat_id', $pid);
		}
		if($cid!=''){
			$this->db->where('scat_id', $cid);
		}
		$this->db->order_by('create_date_time', 'DESC');
		return $this->db->get()->result();
	}
	public function getTestPublish($id)
	{
		$this->db->where('tp_archive', false);
		$this->db->where('test_id', $id);
		return $this->db->get('test_pub')->result();
	}
	public function getTestById($id, $userid)
	{
		$this->db->where('id', $id);
		$this->db->where('user_id', $userid);
		return $this->db->get('test')->result();
	}
	public function getTotalSectionsCount($id, $userid)
	{
		/*$this->db->select('count(sec.id) as sec_count, count(sqm.ques_id) as ques_count')->from('section sec');
		$this->db->join('section_question_map sqm', 'sqm.section_id=sec.id', 'inner');
		$this->db->where('sec.test_id', $id);
		$this->db->where('sec.user_id', $userid);
		return $this->db->get()->result();*/
		$sql = 'select 
					t1.sec_count,t2.ques_count
					from 
				(SELECT count(id) as sec_count FROM section where test_id='.$id.' and user_id='.$userid.') as t1, 
				(SELECT count(sqm.ques_id) as ques_count FROM section_question_map sqm 
				 inner join section sec on sec.id=sqm.section_id 
				 where sec.test_id = '.$id.' and sec.user_id = '.$userid.') as t2';
		return $this->db->query($sql)->result();
	}
	
	public function getAllSectionByTid($id, $userid)
	{
		$this->db->where('test_id', $id);
		$this->db->where('user_id', $userid);
		$this->db->order_by('create_section_time', 'ASC');
		return $this->db->get('section')->result();
	}
	public function getAllQuestionsBySid($id)
	{
		$this->db->select('sqm.*, ques.qbody, ques.marks, tt.type_name')->from('section_question_map sqm');
		$this->db->join('questions ques', 'ques.id=sqm.ques_id', 'inner');
		$this->db->join('test_type tt', 'tt.type_id=ques.type_id', 'inner');
		$this->db->where('sqm.section_id', $id);
		$this->db->order_by('rank', 'ASC');
		return $this->db->get()->result();
	}
	
	public function getQBsByPidCid($catid, $scatid, $userid)
	{
		$this->db->where('cat_id', $catid);
		if($scatid!=0){
			$this->db->where('scat_id', $scatid);
		}
		$this->db->where('user_id', $userid);
		return $this->db->get('question_bank')->result();
	}
	
	public function checkRedundantSecQBs($secid, $qb)
	{
		$this->db->where('sec_id', $secid);
		$this->db->where('qb_id', $qb);
		return $this->db->get('section_qb_map')->num_rows();
	}
	
	public function getTestnTestPublishDetails($tid, $userid)
	{
		$this->db->select('tt.*, tp.publish_type, tp.start_datetm,tp.end_datetm, prog.title as prog_title')->from('test tt');
		$this->db->join('test_pub tp', 'tp.test_id=tt.id', 'inner');
		$this->db->join('program prog', 'prog.id=tt.cat_id', 'inner');
		$this->db->where('tt.id', $tid);
		$this->db->where('tt.user_id', $userid);
		$this->db->where('tp.tp_archive', false);
		return $this->db->get()->result();
	}
	public function getTestnTestPubnProgIds($tid)
	{
		$this->db->select('tt.id as tt_id, tt.cat_id, tp.id as tp_id')->from('test tt');
		$this->db->join('test_pub tp', 'tp.test_id=tt.id', 'inner');
		$this->db->where('tt.id', $tid);
		$this->db->where('tp.tp_archive', false);
		return $this->db->get()->result();
	}
	
	public function getCandidateTestDetails($tp_id, $uid)
	{
		$this->db->where('test_pub_id', $tp_id);
		$this->db->where('can_id', $uid);
		return $this->db->get('runtest_can')->result();
	}


	public function getAllQuestionByUser($uid){
		$this->db->select('ques.*,qqbm.qb_id,tp.type_name as type');
		$this->db->from('questions ques');
		$this->db->join('questions_qb_map qqbm', 'qqbm.ques_id = ques.id', 'left');
		$this->db->join('test_type tp','tp.type_id = ques.type_id');
		$this->db->where('ques.user_id', $uid);
		$this->db->where('qqbm.user_id', $uid);
		return $this->db->get()->result();
	}

	public function getAllOptions(){
		$this->db->select('*');	
		return $this->db->get('options opts')->result();
	}
}