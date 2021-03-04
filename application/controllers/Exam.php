<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "vendor/autoload.php";
require APPPATH . '/libraries/BaseController.php';

class Exam extends BaseController {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member');
		$this->load->model('Course_model');
		$this->load->model('Exam_model');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Kolkata');
		ini_set('memory_limit', '-1');
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
		$mail->FromName = 'Magnox+ Plus';

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
	public function MailSystem($to, $cc, $subject, $message)
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

		$mail->isHTML(true);

		$mail->Subject = $subject;
		$mail->Body = $message;
		//$mail->AltBody = "This is the plain text version of the email content";

		return ($mail->send())? 1 : $mail->ErrorInfo;
	}
	/****************************************************************************/
	
	public function getAllUserQBs()
	{
		$userid = $_SESSION['userData']['userId'];
		$data['qbs'] = $this->Exam_model->getAllUserQuestionBanks($userid);
		/*$i=1;
		foreach($data['qbs'] as $row)
		{
			$qid = $row->id;
			$data['qbqs_'.$i] = $this->Exam_model->getTotalQuestionUnderQBs($qid, $userid);
			$i++;
		}*/
		return $this->load->view('teacher_exam/qb_list', $data);
	}
	public function getQBsByID()
	{
		$qid = trim($_GET['id']);
		echo json_encode($this->Exam_model->getQuestionBankById($qid));
	}
	public function cuQuestionBank()
	{
		$resp = array('status'=>'error', 'msg'=>'Something went wrong.');
		$qid = trim($_POST['qb_id']);
		
		$data['cat_id'] = trim($_POST['qb_prog']);
		$data['scat_id'] = trim($_POST['qb_course']);
		$data['type_id'] = 1;
		$data['user_id'] = $_SESSION['userData']['userId'];
		$data['name'] = trim($_POST['qbtitle']);
		$data['archive_status'] = false;
		$data['explicit_logic'] = "";
		$data['admin_flag'] = true;
		$data['creation_type'] = 1;
		$data['copied_sl'] = 0;
		$data['purpose'] = 1;
		
		if($qid==0){
			$data['create_date_time'] = date('Y-m-d H:i:s');
			if($this->Exam_model->insertData('question_bank', $data)){
				$resp = array('status'=>'success', 'msg'=>'has been added.');
			}else{
				$resp = array('status'=>'error', 'msg'=>'Server error. Please try again.');
			}
		}else{
			$data['modified_date_time'] = date('Y-m-d H:i:s');
			$where = 'id='.$qid;
			if($this->Exam_model->updateData('question_bank', $data, $where)){
				$resp = array('status'=>'success', 'msg'=>'has been updated.');
			}else{
				$resp = array('status'=>'error', 'msg'=>'Server error. Please try again.');
			}
		}
		echo json_encode($resp);
	}
	public function removeQuesBank()
	{
		$qid = trim($_GET['qid']);
		$data['archive_status'] = true;
		$where = 'id='.$qid;
		echo $this->Exam_model->updateData('question_bank', $data, $where);
	}
	
	public function getQuesByID()
	{
		$userid = $_SESSION['userData']['userId'];
		$qsid = trim($_GET['id']);
		echo json_encode($this->Exam_model->getQuestionById($qsid, $userid));
	}
	public function cruQuestion()
	{
		$id = NULL; $qb_id = NULL;
		if(isset($_GET['id'])){
			$id = base64_decode($_GET['id']);
			$where = 'id='.$id;
			$data1['archive_status'] = true;
			$this->Exam_model->updateData('questions', $data1, $where);
		}
		$qb_id = base64_decode($_GET['qbid']);
		$qb = $this->Exam_model->getQuestionBankById($qb_id);
		if($this->isLoggedIn()){
			
			$obj = array('id'=>"0", 'cat_id'=>$qb[0]->cat_id, 'scat_id'=>$qb[0]->scat_id, 'qb_id'=>$qb_id, 'type_id'=>"", 'difficulty_level'=>"", 'weightage'=>"", 'tolerance'=>"", 'qdecimal'=>"", 'marks'=>"", 'qbody'=>"", 'hints'=>"", 'answer'=>"");
			
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Add Question | Magnox Learning+ - Teacher';
			//$data['programs'] = $this->Member->getAllMyPrograms('Teacher', $userid);
			$data['qbs'] = $this->Exam_model->getAllUserQuestionBanks($userid);
			$data['ttype'] = $this->Exam_model->getAllTestTypes();
			if($id==NULL){
				$data['ques'][0] = (object)$obj;
			}else{
				$data['ques'] = $this->Exam_model->getQuestionById($id, $userid);
				$data['qoptions'] = $this->Exam_model->getQuesOptionsById($id);
			}
			$this->loadTeacherExam("add-update-questions", $this->global, $data, NULL);
		}else{
			redirect(base_url().'login');
		}
	}
	public function cuQuestions()
	{
		$mflag=0;
		$data2=[];
		$userid = $_SESSION['userData']['userId'];
		$resp = array('status'=>'error', 'msg'=>'Something went wrong.', 'qbs_qb'=>$_POST['qbs_qb'], 'cat_id'=>$_POST['qbs_prog'], 'scat_id'=>$_POST['qbs_course']);
		$qusid = trim($_POST['qbs_id']);
		
		$data['cat_id'] = trim($_POST['qbs_prog']);
		$data['scat_id'] = trim($_POST['qbs_course']);
		$data['user_id'] = $userid;
		$data['type_id'] = trim($_POST['qbs_test']);
		$data['difficulty_level'] = trim($_POST['qbs_dlvl']);
		$data['weightage'] = trim($_POST['qbs_wht']);
		$data['marks'] = trim($_POST['qbs_marks']);
		$data['used'] = false;
		$data['qbody'] = trim($_POST['qbstitle']);
		$data['archive_status'] = false;
		$data['cal_logic'] = "";
		$data['viewstatus'] = false;
		$data['scale1'] = null;
		$data['scale2'] = null;
		$data['man_flag'] = false;
		$data['multi_select'] = (isset($_POST['ms']))? true : false;
		$data['hints'] = trim($_POST['qbs_hint']);
		$data['answer'] = trim($_POST['qbs_ans']);
		$rf = (isset($_POST['rf']))? true : false;
		if($data['type_id']==4){
			$data['tolerance'] = trim($_POST['tolerance']);
			$data['qdecimal'] = trim($_POST['qdecimal']);
		}
		if($data['type_id']==2){
			//$mcount = trim($_POST['mcount']);
			//$data['random_flag'] = (isset($_POST['rf']))? true : false;
			$mchk = (object)json_decode($_POST['mcqchkboxes']);
			$chk_box = (isset($_POST['chkmcq']))? $_POST['chkmcq'] : 0;
			if(!empty($mchk)){
				$data['create_date_time'] = date('Y-m-d H:i:s');
				$question_id = $this->Exam_model->insertDataRetId('questions', $data);
				if($question_id){
					$k=0;
					foreach($mchk as $row){
						$cf = $row->ckval;
						$body = trim($_POST['mcqs_'.$row->id]);
						$wt = trim($_POST['msnum_'.$row->id]);
						if($body!=""){
							$data2[$k]['ques_id']=$question_id;
							$data2[$k]['body']=$body;
							$data2[$k]['correct_flag']=($cf==1)? true : false;
							$data2[$k]['weightage']=($cf==1)? 100 : 0;
							$data2[$k]['random_flag']=$rf;
							$data2[$k]['create_date_time']=date('Y-m-d H:i:s');
							$this->Exam_model->insertData('options', $data2[$k]);
							$k++;
						}else{
							$mflag++;
						}
					}
					
					$data1['ques_id'] = $question_id;
					$data1['qb_id'] = trim($_POST['qbs_qb']);
					$data1['user_id'] = $userid;
					$data1['status'] = true;
					$data1['create_date_time'] = date('Y-m-d H:i:s');
					$data1['qb_order'] = $question_id;
					if($this->Exam_model->insertData('questions_qb_map', $data1)){
						$resp = array('status'=>'success', 'msg'=>"was added successfully.");
					}else{
						$resp = array('status'=>'error', 'msg'=>"was added, but couldn't link with question bank.");
					}
				}else{
					$resp = array('status'=>'error', 'msg'=>"was not added. Server failed.");
				}
			}else{
				$resp = array('status'=>'error', 'msg'=>"was not added, because no options were included.");
			}
		}else{
			$data['create_date_time'] = date('Y-m-d H:i:s');
			$question_id = $this->Exam_model->insertDataRetId('questions', $data);
			if($question_id){
				$data1['ques_id'] = $question_id;
				$data1['qb_id'] = trim($_POST['qbs_qb']);
				$data1['user_id'] = $userid;
				$data1['status'] = true;
				$data1['create_date_time'] = date('Y-m-d H:i:s');
				$data1['qb_order'] = $question_id;
				if($this->Exam_model->insertData('questions_qb_map', $data1)){
					$resp = array('status'=>'success', 'msg'=>"was added successfully.");
				}else{
					$resp = array('status'=>'error', 'msg'=>"was added, but couldn't link with question bank.");
				}
			}else{
				$resp = array('status'=>'error', 'msg'=>"was not added. Server failed.");
			}
		}
		
		echo json_encode($resp);
	}
	public function getAllUserQues()
	{
		$qbid = trim($_GET['qbid']);
		$userid = $_SESSION['userData']['userId'];
		$data['ques'] = $this->Exam_model->getAllUserQuestions($qbid, $userid);
		$i=1;
		foreach($data['ques'] as $row){
			$id = $row->id;
			$data['ops_'.$i] = $this->Exam_model->getAllOptionsByQid($id);
			$i++;
		}
		return $this->load->view('teacher_exam/ques_list', $data);
		
	}
	public function removeQuestion()
	{
		$qid = trim($_GET['qid']);
		$data['archive_status'] = true;
		$where = 'id='.$qid;
		echo $this->Exam_model->updateData('questions', $data, $where);
	}
	
	/*******************************************TEST*********************************************/
	public function getAllUserTests()
	{
		$userid = $_SESSION['userData']['userId'];
		$pid = trim($_GET['pid']);
		$cid = trim($_GET['cid']);
		$data['tests'] = $this->Exam_model->getAllUserTests($userid, $pid, $cid);
		$i=1;
		foreach($data['tests'] as $row){
			$id = $row->id;
			$data['ttp_'.$i] = $this->Exam_model->getTestPublish($id);
			$data['sec_'.$i] = $this->Exam_model->getTotalSectionsCount($id, $userid);
			$i++;
		}
		return $this->load->view('teacher_exam/test_list', $data);
	}
	public function cuTest()
	{
		$resp = array('status'=>'error', 'msg'=>"was not created. Something went wrong.");
		$tid = trim($_POST['tt_id']);
		$userid = $_SESSION['userData']['userId'];
		
		$data['title'] = trim($_POST['tt_title']);
		$data['cat_id'] = trim($_POST['tt_prog']);
		$data['scat_id'] = trim($_POST['tt_course']);
		$data['user_id'] = $userid;
		$data['details'] = trim($_POST['tt_details']);
		$data['admin_flag'] = true;
		$data['archive_status'] = false;
		$data['duration'] = trim($_POST['tt_time']);
		$data['marks'] = trim($_POST['tt_marks']);
		$data['publish'] = false;
		
		if($tid==0){
			$data['create_date_time'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('test', $data);
			$resp = array('status'=>'success', 'msg'=>"was added successfully.");
			
		}else{
			$data['modified_date_time'] = date('Y-m-d H:i:s');
			$where = 'id='.$tid;
			$this->Exam_model->updateData('test', $data, $where);
			$resp = array('status'=>'success', 'msg'=>"was updated successfully.");
		}
		echo json_encode($resp);
	}
	public function getTestByID()
	{
		$userid = $_SESSION['userData']['userId'];
		$ttid = trim($_GET['id']);
		echo json_encode($this->Exam_model->getTestById($ttid, $userid));
	}
	public function unPublishTest()
	{
		$tid = trim($_GET['tid']);
		$data['publish'] = false;
		$where = 'id='.$tid;
		$where1 = "test_id=".$tid." AND tp_archive=false";
		$data1['tp_archive'] = true;
		$this->Exam_model->updateData('test_pub', $data1, $where1);
		echo $this->Exam_model->updateData('test', $data, $where);
	}
	public function publishTest()
	{
		$userid = $_SESSION['userData']['userId'];
		$tid = trim($_POST['tid']);
		$data['publish'] = true;
		$where = 'id='.$tid;
		if($this->Exam_model->updateData('test', $data, $where)){
			$data1['test_id'] = $tid;
			$data1['user_id'] = $userid;
			$data1['publish_type'] = trim($_POST['launch']);
			if($data1['publish_type']==2){
				$data1['start_datetm'] = date('Y-m-d H:i:s',strtotime($_POST['sdatetime']));
			}else if($data1['publish_type']==3){
				$data1['start_datetm'] = date('Y-m-d H:i:s',strtotime($_POST['sdatetime']));
				$data1['end_datetm'] = date('Y-m-d H:i:s',strtotime($_POST['edatetime']));
			}
			$data1['tp_archive'] = false;
			$data1['create_date_time'] = date('Y-m-d H:i:s');
			echo $this->Exam_model->insertData('test_pub', $data1);
		}else{
			echo false;
		}
	}
	public function removeTest()
	{
		$tid = trim($_GET['tid']);
		$data['archive_status'] = true;
		$where = 'id='.$tid;
		echo $this->Exam_model->updateData('test', $data, $where);
	}
	
	public function getAllSections()
	{
		$userid = $_SESSION['userData']['userId'];
		$id = trim($_GET['id']);
		$data['sections'] = $this->Exam_model->getAllSectionByTid($id, $userid);
		$i=1;
		foreach($data['sections'] as $row){
			$sid = $row->id;
			$data['ques_'.$i] = $this->Exam_model->getAllQuestionsBySid($sid);
			$i++;
		}
		return $this->load->view('teacher_exam/section_list', $data);
	}
	public function cuSection()
	{
		$resp = array('status'=>'error', 'msg'=>'was stuck in server. Please try again.');
		$userid = $_SESSION['userData']['userId'];
		$sid = $_POST['scid'];
		
		$data['test_id'] = trim($_POST['tid']);
		$data['user_id'] = $userid;
		$data['type_id'] = 1;
		$data['section_name'] = trim($_POST['sectitle']);
		$data['random_flag'] = false;

		if($sid==0){
			$data['create_section_time'] = date('Y-m-d H:i:s');
			$this->Exam_model->insertData('section', $data);
			$resp = array('status'=>'success', 'msg'=>'was created successfully.');
		}else{
			$where = 'id='.$sid;
			$data['modified_date_time'] = date('Y-m-d H:i:s');
			$this->Exam_model->updateData('section', $data, $where);
			$resp = array('status'=>'success', 'msg'=>'was updated successfully.');
		}
		echo json_encode($resp);
	}
	public function updateSecRandom()
	{
		$sid = trim($_POST['sid']);
		$data['random_flag'] = trim($_POST['rdfg']);
		$data['ques_number'] = trim($_POST['qnum']);
		$data['benchmark'] = trim($_POST['bnch']);
		$where = 'id='.$sid;
		$data['modified_date_time'] = date('Y-m-d H:i:s');
		echo $this->Exam_model->updateData('section', $data, $where);
	}
	public function removeSection()
	{
		$sid = trim($_GET['sid']);
		$data['archive_status'] = true;
		$where = 'id='.$sid;
		echo $this->Exam_model->updateData('section', $data, $where);
	}
	public function getAllQBsQuestions()
	{
		$userid = $_SESSION['userData']['userId'];
		$catid = $_GET['catid'];
		$scatid = $_GET['scatid'];
		$data['sid'] = $_GET['sid'];
		$data['qbks'] = $this->Exam_model->getQBsByPidCid($catid, $scatid, $userid);
		$i=1;
		foreach($data['qbks'] as $tup){
			$qbid = $tup->id;
			$data['ques_'.$i] = $this->Exam_model->getAllSelectedUserQuestions($qbid, $userid, $data['sid']);
			$j=1;
			foreach($data['ques_'.$i] as $row){
				$id = $row->id;
				$data['ops_'.$j] = $this->Exam_model->getAllOptionsByQid($id);
				$j++;
			}
			$i++;
		}
		return $this->load->view('teacher_exam/add_questions_list', $data);
	}
	public function cuSecQuestion()
	{
		$flag1 = 0;
		$flag2 = 0;
		$flag3 = 0;
		$resp = array('status'=>'danger', 'msg'=>'Something went wrong.');
		$secid = trim($_POST['secid']);
		$qbcount = trim($_POST['qbcount']);
		$where = 'sec_id='.$secid;
		$where1 = 'section_id='.$secid;
		$this->Exam_model->deleteData('section_qb_map', $where);
		$this->Exam_model->deleteData('section_question_map', $where1);
		
		for($i=1; $i<=$qbcount; $i++){
			$qb = $_POST['qbid_'.$i];
			$chksqb = $this->Exam_model->checkRedundantSecQBs($secid, $qb);
			if(isset($_POST['chk_ques'.$i])){
				$data1[$i]['sec_id'] = $secid;
				$data1[$i]['qb_id'] = $qb;
				$data1[$i]['add_datetime'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('section_qb_map', $data1[$i]);
				$flag1++;
				$queslist = $_POST['chk_ques'.$i];
				$cques = count($queslist);
				for($j=0; $j<$cques; $j++){
					$data2[$j]['section_id'] = $secid;
					$data2[$j]['ques_id'] = $queslist[$j];
					$data2[$j]['type_id'] = 1;
					$data2[$j]['rank'] = ($j+1);
					$data2[$j]['status'] = true;
					$data2[$j]['flag'] = true;
					$data2[$j]['create_date_time'] = date('Y-m-d H:i:s');
					$this->Exam_model->insertData('section_question_map', $data2[$j]);
					$flag2++;
				}
			}else{
				$flag3++;
			}
		}
		if($flag3>0 && $flag2==0 && $flag1==0){
			$resp = array('status'=>'danger', 'msg'=>'No questions were selected.');
		}else{
			$resp = array('status'=>'success', 'msg'=>$flag1.' new Question Bank(s) and '.$flag2.' questions were added into the section.');
		}
		echo json_encode($resp);
	}
	/**************************************************************/
	function getAllTestCandidates()
	{
		$userid = $_SESSION['userData']['userId'];
		$tid = trim($_GET['id']);
		$data['testpub'] = $this->Exam_model->getTestnTestPubnProgIds($tid);
		if(!empty($data['testpub'])){
			$prog_id = $data['testpub'][0]->cat_id;
			$tp_id = $data['testpub'][0]->tp_id;
			$data['candi'] = $this->Member->getAllRequestByIdRole($prog_id, 'Student', NULL, 'accepted');
			$i=1;
			foreach($data['candi'] as $row){
				$uid = $row->id;
				$data['rtc_'.$i] = $this->Exam_model->getCandidateTestDetails($tp_id, $uid);
				$i++;
			}
		}
		return $this->load->view('teacher_exam/candidate_list', $data);
	}
	function singleTestInvite()
	{
		$userid = $_SESSION['userData']['userId'];
		$this->load->helper('string');
		$code = random_string('numeric', 6);
		$tid = trim($_POST['ttid']);
		$uid = trim($_POST['uid']);
		$testtp = $this->Exam_model->getTestnTestPublishDetails($tid, $userid);
		$data['test_pub_id'] = trim($_POST['tp_id']);
		$data['can_id'] = $uid;
		$data['pswd'] = $code;
		$data['present_ques_id'] = 0;
		$data['sec_id'] = 0;
		$data['test_start_flag'] = false;
		$data['test_end_flag'] = false;
		$data['create_date_time'] = date('Y-m-d H:i:s');
		$users = $this->Member->getStudentNameEmailById($uid);
		$subject = 'New Test Notification';
		$message = '<h4>Test Details</h4><strong>Test: </strong>'.trim($testtp[0]->title).'<br><strong>Program: </strong>'.trim($testtp[0]->prog_title).'<strong>Duration: </strong>'.trim($testtp[0]->duration).' minutes; <strong>Marks: </strong>'.trim($testtp[0]->marks).'<br>';
		$pt = (int)trim($testtp[0]->publish_type);
		if($pt==1){
			$message.= '<strong>Exam time: </strong>Anytime';
		}else if($pt==2){
			$message.= '<strong>Exam time: </strong>'.date('jS M Y h:ia',strtotime($testtp[0]->start_datetm));
		}else if($pt==3){
			$message.= '<strong>Exam time: </strong>'.date('jS M Y h:ia',strtotime($testtp[0]->start_datetm)).' - '.date('jS M Y h:ia',strtotime($testtp[0]->end_datetm));
		}
		$message.='<h6>Exam login password: '.$code.'</h6><br><br>Thanking you<br>Magnox Learning+';
		
		$this->MultipleMailSystem($users, $subject, $message);
		
		echo $this->Exam_model->insertData('runtest_can', $data);
	}
	function multipleTestInvite()
	{
		$flag=0;
		$userid = $_SESSION['userData']['userId'];
		$this->load->helper('string');
		
		$uid = $_POST['uid'];
		$cuid = count($uid);
		
		$tid = trim($_POST['ttid']);
		$tp_id = trim($_POST['tp_id']);
		$testtp = $this->Exam_model->getTestnTestPublishDetails($tid, $userid);
		 
		if($cuid>0){
			for($i=0; $i<$cuid; $i++){
				$code = random_string('numeric', 6);
				
				$data[$i]['test_pub_id'] = trim($_POST['tp_id']);
				$data[$i]['can_id'] = $uid[$i];
				$data[$i]['pswd'] = $code;
				$data[$i]['present_ques_id'] = 0;
				$data[$i]['sec_id'] = 0;
				$data[$i]['test_start_flag'] = false;
				$data[$i]['test_end_flag'] = false;
				$data[$i]['create_date_time'] = date('Y-m-d H:i:s');
				$this->Exam_model->insertData('runtest_can', $data[$i]);
				$flag++;
				$users = $this->Member->getStudentNameEmailById($uid[$i]);
				//print_r($users); exit;
				$subject = 'New Test Notification';
				$message = '<h4>Test Details</h4><strong>Test: </strong>'.trim($testtp[0]->title).'<br><strong>Program: </strong>'.trim($testtp[0]->prog_title).'<strong>Duration: </strong>'.trim($testtp[0]->duration).' minutes; <strong>Marks: </strong>'.trim($testtp[0]->marks).'<br>';
				$pt = (int)trim($testtp[0]->publish_type);
				if($pt==1){
					$message.= '<strong>Exam time: </strong>Anytime';
				}else if($pt==2){
					$message.= '<strong>Exam time: </strong>'.date('jS M Y h:ia',strtotime($testtp[0]->start_datetm));
				}else if($pt==3){
					$message.= '<strong>Exam time: </strong>'.date('jS M Y h:ia',strtotime($testtp[0]->start_datetm)).' - '.date('jS M Y h:ia',strtotime($testtp[0]->end_datetm));
				}
				$message.='<h6>Exam login password: '.$code.'</h6><br><br>Thanking you<br>Magnox Learning+';
				
				$this->MultipleMailSystem($users, $subject, $message);
			}
			
			echo ($flag>0)? true : false;
		}else{
			echo false;
		}
	}
	/*****************************************************************/

	public function testReport($user_id,$test_id,$test_pub_id){
		if($this->isLoggedIn()){
			$userid = $_SESSION['userData']['userId'];
			$this->global['pageTitle'] = 'Test Details | Magnox Learning+ - Teacher';
			$data = array();
			$i    = 0;
			$data['user_details'] = $this->Member->getSingleStudent(base64_decode($user_id));
			$data['test_details'] = $this->Member->testDetailsById(base64_decode($test_id));
			$section              = $this->Member->getSectionById(base64_decode($test_id));
			$question             = $this->Member->allQuestionsOfTest(base64_decode($user_id),base64_decode($test_pub_id));
			$correct_ans          = $this->Member->getCorrectAnswer();
			$given_ans            = $this->Member->getGivenAnswer();
			$question_answer      = array();

			foreach($section as $sec){
				foreach($question as $ques){
					if($sec->id == $ques->sec_id){
						$question_answer[$sec->section_name][$i] = array(
							'question_id'      => $ques->id,
						    'section'          => $ques->sec_id,
						    'question'         => $ques->qbody,
							'difficulty_level' => $ques->difficulty_level,
							'weightage'        => $ques->weightage,
							'marks'            => $ques->marks,
							'type'             => $ques->type_name,
							'type_id'          => $ques->type_id
						);
						if($ques->type_id == 2){
							foreach($correct_ans as $ca){
								if($ques->id == $ca->ques_id){
									$question_answer[$sec->section_name][$i]['correct_answer']    = $ca->body;
									$question_answer[$sec->section_name][$i]['correct_answer_id'] = $ca->id;
									$question_answer[$sec->section_name][$i]['answer']            = !empty($ques->answer)?$ques->answer : '';
									$question_answer[$sec->section_name][$i]['hint']              = !empty($ques->hints)?$ques->hints : '';
								}
							}
							foreach($given_ans as $ga){
								if($ques->id == $ga->ques_id){
									$question_answer[$sec->section_name][$i]['given_answer'] = $ga->body;
									$question_answer[$sec->section_name][$i]['given_answer_id'] = $ga->id;
								}
							}
						}else{
							$question_answer[$sec->section_name][$i]['correct_answer'] = !empty($ques->answer)?$ques->answer : '';
							$question_answer[$sec->section_name][$i]['hint']           = !empty($ques->hints)?$ques->hints : '';
							$question_answer[$sec->section_name][$i]['given_answer']   = $ques->ans_body;
						}
					}
					$i++;
				}
			}
			$data['test_report'] = $question_answer;
			$this->loadTeacherExam("exam-report", $this->global, $data, NULL);
		}else{
			redirect(base_url());
		}		
	}
}