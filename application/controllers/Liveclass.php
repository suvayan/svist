<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Liveclass extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
	}
	public function MailSystem($to, $cc, $bcc, $subject, $message)
	{
		$email = "admin@billionskills.com";
        $password = "Magic#123";

		$mail = new PHPMailer(true);
		//Enable SMTP debugging.
		$mail->SMTPDebug = 0;                               
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();            
		//Set SMTP host name  
		$mail->CharSet = 'utf-8';// set charset to utf8
		//$mail->Encoding = 'base64';
		$mail->SMTPAuth = true;// Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted

		$mail->Host = 'lotus.arvixe.com';// Specify main and backup SMTP servers
		$mail->Port = 465;// TCP port to connect to
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);		                     
		//Provide username and password     
		$mail->Username = $email;                 
		$mail->Password = $password;                                                                         

		$mail->From = $email;
		$mail->FromName = 'Magnox+ Plus';

		$mail->addAddress($to);
		$mail->addCC($cc);
		$mail->addBCC($bcc);

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";

		return ($mail->send())? 1 : $mail->ErrorInfo;
		$mail->clearAddresses();
	}
	public function MultipleMailSystem($users, $subject, $message)
	{
		$email = "admin@billionskills.com";
        $password = "Magic#123";

		$mail = new PHPMailer(true);
		//Enable SMTP debugging.
		$mail->SMTPDebug = 0;                               
		//Set PHPMailer to use SMTP.
		$mail->isSMTP();            
		//Set SMTP host name  
		$mail->CharSet = 'utf-8';// set charset to utf8
		//$mail->Encoding = 'base64';
		$mail->SMTPAuth = true;// Enable SMTP authentication
		$mail->SMTPSecure = 'ssl';// Enable TLS encryption, `ssl` also accepted

		$mail->Host = 'lotus.arvixe.com';// Specify main and backup SMTP servers
		$mail->Port = 465;// TCP port to connect to
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);		                     
		//Provide username and password     
		$mail->Username = $email;                 
		$mail->Password = $password;                                                                         

		$mail->From = $email;
		$mail->FromName = 'SVIST.org';

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";
		foreach ($users as $user) {
		  $mail->addAddress($user['email'], $user['name']);

		  $mail->Body = "<h2>Hello, ".$user['name']."</h2> ".$message."</p>";
		  //$mail->AltBody = "Hello, {$user['name']}! \n How are you?";

		  try {
			  $mail->send();
			  //echo "Message sent to: ({$user['email']}) {$mail->ErrorInfo}\n";
		  } catch (Exception $e) {
			  //echo "Mailer Error ({$user['email']}) {$mail->ErrorInfo}\n";
		  }

		  $mail->clearAddresses();
		}

		return true;//($mail->send())? 1 : $mail->ErrorInfo;
	}
	
	/****************************************TEACHER******************************************/
	public function getLiveClasses(){
		$res = array();
		$userid = $_GET['teacher_id'];
		$from = $_GET['from'];
		$to = $_GET['to'];
		$live_classes = $this->Member->getLiveClasses($userid,$from,$to);
		$res['status'] = 'success';
		$res['data'] = $live_classes;
		echo json_encode($res);
	}

	public function startLiveClass()
	{
		$res = array();
		if($this->isLoggedIn()){
			$data['course_id'] = $_POST['course_id'];
			$data['room_name'] = $_POST['room_name'];
			$data['start_time'] = date('Y-m-d H:i:s');
			$data['teacher_id'] = $_SESSION['userData']['userId'];

			// check if there is aleray a live class
			$lclass = $this->Member->getLiveClassByCid($data['course_id']);
			if(count($lclass) == 0){
				if($this->Member->insertLiveClass($data)){
					$res['status'] = 'success';
					$res['msg'] = 'live_class inserted successfully';
					$res['room'] = $data['room_name'];
					$lclass = $this->Member->getLiveClassByCid($data['course_id']);
					$res['live_id'] = $lclass[0]->id;
				}else{
					$res['status'] = 'error';
					$res['msg'] = 'could not insert into live_class';
				}		
			}else{
				$res['status'] = 'success';
				$res['msg'] = 'Live Class is already started';
				$res['room'] = $lclass[0]->room_name;
				$res['live_id'] = $lclass[0]->id;
			}				
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}

	public function stopLiveClass()
	{
		$res = array();
		if($this->isLoggedIn()){
			$cid = $_POST['course_id'];
			$data['active'] = false;
			$data['end_time'] = date('Y-m-d H:i:s');

			
			if($this->Member->updateLiveClass($cid, $data)){
				$res['status'] = 'success';
				$res['msg'] = 'live_class updated successfully';
			}else{
				$res['status'] = 'error';
				$res['msg'] = 'could not update live_class';
			}				
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}

	// public function test(){
	// 	$live_id = $_GET['live_id'];
	// 	$invitations = $this->Member->getAllLiveClassInvitationByLiveId($live_id);
	// 	echo count($invitations);
	// }

	public function inviteStudents()
	{
		$res = array();
		if($this->isLoggedIn()){
			$cid = $_POST['cid'];
			$toemail = $_SESSION['userData']['email'];
			$progcourse = $this->Member->getProgramCourse($cid);
			$live_id = $_POST['live_id'];
			$students = $_POST['students'];
			$status = 'success';
			$msg = '';
			$subject = 'Notice for Live Class';
			$message = 'You have been invited to join the live class for <strong>Program: </strong>'.$progcourse[0]->program_title.'=><strong>Course: </strong>'.$progcourse[0]->course_title.'.<br> Please log in to your dashboard <a href="'.base_url().'" target="_blank">here</a><br><br>SVIST.org';
			// get all previous invitation
			$invitations = $this->Member->getAllLiveClassInvitationByLiveId($live_id);
			$users = [];
			
			foreach ($invitations as $inv) {
				array_push($users, $inv->student_id);
			}
			$bcc = $this->Member->getStudEmailNameById($students);
			if($this->MultipleMailSystem($bcc, $subject, $message)){
				foreach ($students as $student) {
					if(!in_array($student, $users)){
						$data['live_class_id'] = $live_id;
						$data['student_id'] = $student;
						$data['inv_time'] = date('Y-m-d H:i:s');
						if(!$this->Member->insertLiveClassInvitation($data)){					
							$status = 'error';
							$msg = "Could not insert live_class_students for user_id: ".$student;
							break;		
						}
					}				
				}
				$res['status'] = $status;
				$res['msg'] = $msg;
				if($status == 'success'){
					$res['msg'] = "All students are invited successfully";
				}
			}else{
				$res['msg'] = "Mail sent error.";
			}
			
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}
	
	public function takeAttendance()
	{
		$res = array();
		if($this->isLoggedIn()){
			$live_id = $_POST['live_id'];
			$cid = $_POST['course_id'];
			$userid = $_SESSION['userData']['userId'];

			// get prog id
			$progcourses = $this->Member->getProgramCourse($cid);
			$pid = $progcourses[0]->pid;

			// get all previous invitation
			$invitations = $this->Member->getAllLiveClassInvitationByLiveId($live_id);
			$data = [];
			
			foreach ($invitations as $inv) {
				if($inv->joined == 't'){
					$data['prog_id'] = $pid;
					$data['teacher_id'] = $userid;
					$data['course_id'] = $cid;
					$data['student_id'] = $inv->student_id;
					$data['a_datetime'] = date('Y-m-d H:i:s');
					$data['class_type'] = 0;
					$data['live_id'] = $live_id;
					$data['status'] = 'P';					
				}else{
					$data['prog_id'] = $pid;
					$data['teacher_id'] = $userid;
					$data['course_id'] = $cid;
					$data['student_id'] = $inv->student_id;
					$data['a_datetime'] = date('Y-m-d H:i:s');
					$data['class_type'] = 0;
					$data['live_id'] = $live_id;
					$data['status'] = 'A';
				}
				$this->Member->takeAttendance($data);
			}		
			$res['status'] = 'success';
			$res['msg'] = "Attendance recorded successfully";
			
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}


	public function getAttendance(){
		$res = array();
		if($this->isLoggedIn()){			
			$live_id = $_GET['live_id'];
			$attendance = $this->Member->getAttendance($live_id);	
			$res['status'] = 'success';
			$res['data'] = $attendance;
			//$res['live_id'] = $live_id;
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}

	public function getInvitationStatus(){
		$res = array();
		if($this->isLoggedIn()){
			$live_id = $_POST['live_id'];
			$invitations = $this->Member->getAllLiveClassInvitationByLiveId($live_id);
			$res['data'] = $invitations;
			$res['status'] = 'success';
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not loggged in';
		}
		echo json_encode($res);
	}
	
	/****************************************STUDENT******************************************/
	public function getAllMyInvitation()
	{
		$res = array();
		$cid = null;
		$userid = $_GET['user_id'];
		if($this->isLoggedIn()){
			$inv = $this->Member->getAllMyInvitation($userid);
			if(count($inv)){
				$res['status'] = 'success';
				$res['data'] = $inv;
				$res['msg'] = 'live class started';
			}else{
				$res['status'] = 'error';
				$res['msg'] = 'No live class invitation found';
			}
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not logged in';
		}
		echo json_encode($res);
	}

	public function getMyInvitation()
	{
		$res = array();
		$cid = null;
		$cid = $_POST['course_id'];
		$userid = $_POST['user_id'];
		if($this->isLoggedIn()){
			$inv = $this->Member->getMyInvitation($userid, $cid);
			if(count($inv)){
				$res['status'] = 'success';
				$res['room_name'] = $inv[0]->room_name;
				$res['id'] = $inv[0]->live_class_id;
				$res['msg'] = 'live class started';
			}else{
				$res['status'] = 'error';
				$res['msg'] = 'No live class invitation found';
			}
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not logged in';
		}
		echo json_encode($res);
	}

	
	public function joinMeeting(){
		$res = array();
		$lc_id = $_POST['lc_id'];
		$userid = $_SESSION['userData']['userId'];
		if($this->isLoggedIn()){
			$data['start_time'] = date('Y-m-d H:i:s');
			$data['joined'] = true;
			if($this->Member->joinMeeting($lc_id, $userid, $data)){
				$res['status'] = 'success';
				$res['msg'] = 'joined the meeting successfully';
			}else{
				$res['status'] = 'error';
				$res['msg'] = 'could not join meeting';
			}
						
		}else{
			$res['status'] = 'error';
			$res['msg'] = 'not logged in';
		}
		echo json_encode($res);
	}
}