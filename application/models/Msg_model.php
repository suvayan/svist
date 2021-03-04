<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class Msg_model extends CI_Model {
	
	public function getAllUserList($userid, $utype)
	{
		$sql = "select pur.user_id, CONCAT(ud.first_name,' ',ud.last_name) as uname, pur.role, pur.program_id, prog.title 
				from pro_users_role pur
				inner join user_details ud on ud.id=pur.user_id
				inner join program prog on prog.id=pur.program_id
				where pur.program_id in (select program_id from pro_users_role where user_id=".$userid.")
				and pur.role='".$utype."'
				and pur.user_id <> ".$userid."
				and pur.status = 'accepted'
				order by ud.first_name asc";
		return $this->db->query($sql)->result();
	}
	
	public function getUserLastActive($uid)
	{
		$this->db->select('status')->from('login_track');
		$this->db->where('user_id', $uid);
		$this->db->order_by('login_datetime', 'DESC');
		$this->db->limit('1');
		return $this->db->get()->result();
	}
	
	public function getUserListSMS($userid)
	{
		$this->db->select('user_details.id, first_name, last_name')->from('message_all');
		$this->db->join('user_details', 'user_details.id=message_all.to_sl', 'inner');
		$this->db->where('message_all.from_sl', $userid);
		$this->db->order_by('message_all.datetime', 'DESC');
		$query1 = $this->db->get_compiled_select();
		
		$this->db->select('user_details.id, first_name, last_name')->from('message_all');
		$this->db->join('user_details', 'user_details.id=message_all.from_sl', 'inner');
		$this->db->where('message_all.to_sl', $userid);
		$this->db->order_by('message_all.datetime', 'DESC');
		$this->db->group_by('user_details.id, message_all.datetime');
		$query2 = $this->db->get_compiled_select();
		
		return $this->db->query("(".$query1.") UNION DISTINCT (".$query2.")")->result();
	}
	
	public function insertUserChat($data)
	{
		return $this->db->insert('message_all', $data);
	}
	
	public function updateReadStatus($to_user_id, $from_user_id)
	{
		$this->db->set('to_status', 1);
		$this->db->where('to_sl', $to_user_id);
		$this->db->where('from_sl', $from_user_id);
		return $this->db->update('message_all');
	}
	
	public function getTotalUnreadMsgs($to_user_id, $from_user_id)
	{
		$this->db->where('to_sl', $to_user_id);
		$this->db->where('from_sl', $from_user_id);
		$this->db->where('to_status', 0);
		return $this->db->get('message_all')->num_rows();
	}
	
	public function getUserChats($from_user_id, $to_user_id)
	{
		$sql = "SELECT * FROM message_all 
		WHERE (from_sl = '".$from_user_id."' 
		AND to_sl = '".$to_user_id."') 
		OR (from_sl = '".$to_user_id."' 
		AND to_sl = '".$from_user_id."') 
		ORDER BY datetime ASC";
		 
		return $this->db->query($sql)->result();
	}
}