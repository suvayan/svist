<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Message extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member');
		$this->load->model('Course_model');
		$this->load->model('Msg_model');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
	}
	
	public function getAllConnectedUsers()
	{
		$userid = $_SESSION['userData']['userId'];
		$utype = ucfirst(trim($_GET['ut']));
		$data['userList'] = $this->Msg_model->getAllUserList($userid, $utype);
		$i=1;
		foreach($data['userList'] as $row){
			$uid = $row->user_id;
			$data['loggedId_'.$i] = $this->Msg_model->getUserLastActive($uid);
			$i++;
		}
		return $this->load->view('sms_chat/user_list', $data);
	}
	
	public function getToFroMsgUsers()
	{
		$userid = $_SESSION['userData']['userId'];
		$data['usms'] = $this->Msg_model->getUserListSMS($userid);
		$i=1;
		foreach($data['usms'] as $row)
		{
			$fid = $row->id;
			$data['unread_'.$i] = $this->Msg_model->getTotalUnreadMsgs($userid, $fid);
			$i++;
		}
		return $this->load->view('sms_chat/user_message', $data);
	}
	
	public function insertChat()
	{
		$from_id = $_SESSION['userData']['userId'];
		$to_id = trim($_POST['to_user_id']);
		$chat_sms = trim($_POST['chat_message']);
		if($chat_sms!=""){
			$data['to_sl'] = $to_id;
			$data['from_sl'] = $from_id;
			$data['textmsg'] = $chat_sms;
			$data['datetime'] = date('Y-m-d H:i:s');
			$data['status']=1;
			$data['to_status']=0;
			$data['from_status']=1;
			
			return $this->Msg_model->insertUserChat($data);
		}
	}
	
	public function fetchUserChatHistory()
	{
		$data['mainId'] = $_SESSION['userData']['userId'];
		$usersl = trim($_GET['uid']);
		$this->Msg_model->updateReadStatus($data['mainId'], $usersl);

		$data['userchat'] = $this->Msg_model->getUserChats($data['mainId'], $usersl);
		
		return $this->load->view('sms_chat/user_chat', $data);
	}
}