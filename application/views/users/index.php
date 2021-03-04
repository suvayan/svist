<style>
#course_code::placeholder {
	color: white;
}
.fa-spinner {
	animation-name: spin;
	animation-duration: 5000ms;
	animation-iteration-count: infinite;
	animation-timing-function: linear;
}
@keyframes spin {
    from {
        transform:rotate(0deg);
    }
    to {
        transform:rotate(360deg);
    }
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
		
			<div class="col-lg-8 col-md-8">
				<div id="main-container">
				<div class="row">
					<div class="col-md-4">
						<div class="card">
						  <div class="card-header card-header-rose card-header-icon">
							<div class="card-icon" id="ricon">
							  <i class="material-icons">verified</i>
							</div>
						  </div>
						  <div class="card-body">
							<a href="javascript:;" onClick="getUserData('resume');"><p class="card-category">My Resume</p></a>
						  </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
						  <div class="card-header card-header-rose card-header-icon">
							<div class="card-icon" id="sicon">
							  <i class="material-icons">bar_chart</i>
							</div>
						  </div>
						  <div class="card-body">
							<a href="javascript:;" onClick="getUserData('skills');"><p class="card-category">Skills</p></a>
						  </div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card">
						  <div class="card-header card-header-success card-header-icon">
							<div class="card-icon">
							  <i class="material-icons">video_call</i>
							</div>
						  </div>
						  <div class="card-body">
							<p class="card-category">Video Cover</p>
						  </div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">My Programs</h3>
					</div>
					<div class="card-body">
						<div id="accordion" role="tablist">
							<?php $i=1; if(count($programs)>0){ foreach($programs as $prow){ 
								$id = $prow->id;
								$title = $prow->title;
							?>
								<div class="card-collapse" id="card_<?php echo $id; ?>">
								  <div class="card-header" role="tab" id="heading<?php echo $i; ?>">
									<h5 class="mb-0">
									  <a class="collapsed" data-toggle="collapse" href="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
										<?php echo $i.". ".$title; ?>
										<i class="material-icons">keyboard_arrow_down</i>
									  </a>
									</h5>
								  </div>
								  <div id="collapse<?php echo $i; ?>" class="collapse" role="tabpanel" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
									<div class="card-body">
										<div class="row">
											<div class="col-md-10">
												
												  <?php
														$type = trim($prow->ptype);
														$category = trim($prow->category);
														if($type!=""){
															echo '<a href="javascript:;" class="btn btn-info btn-sm">'.$type.'</a>';
														}
														if($category!=""){
															echo '<a href="javascript:;" class="btn btn-sm bg-light text-dark">'.$category.'</a>';
														}
														echo '<a href="'.base_url('Student/viewProgram/?id='.base64_encode($id)).'" class="btn btn-warning btn-sm"><i class="material-icons">pageview</i> View</a>';
														if(count(${'pcourses'.$i})>0){
															echo '<br>This program has the following course/s. Click to view them.';
															echo '<ol type="1">';
															foreach(${'pcourses'.$i} as $pcow){
																echo '<li><a href="'.base_url().'Student/courseDetails/?cid='.base64_encode($pcow->id).'&prog='.base64_encode($prow->id).'">'.$pcow->title.' ('.$pcow->c_code.')</a></li>';
															}
															echo '</ol>';
														}
												  ?>
											</div>
										</div>
									</div>
								  </div>
								</div>
							<?php $i++;} }else{ echo '<h4 class="text-center">Click <u><a href="'.base_url('Student/requestPrograms').'">here</a></u> to view status of your applied programs.</h4>'; } ?>
						</div>
					</div>
				</div>
				</div>
				<div id="jitsi-container" style="min-height:400px; display:none;"></div>
			</div>
			<aside class="col-lg-4 col-md-4">
				<!--<div class="card bg-warning text-white">
					<div class="card-body">
						<form id="frmLive">
							<input type="text" name="course_code" id="course_code" class="form-control text-white" placeholder="Enter Course code.." required="true">
							<button class="btn bg-dark btn-block" id="liveBt" type="submit">Join Online Class</button>
						</form>
					</div>
				</div>-->
				<div class="card mt-0" id="live-button-container" style="display:none">
					<div class="card-body">
						<div id="live-buttons">
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">Notices</h3>
					</div>
					<div class="card-body">
						<div class="notice_ticker" style="height:30vh;">
							<ul class="list-group" id="notc_details">
							
							</ul>
						</div>
					</div>
					<div class="card-footer">
						<a href="<?php echo base_url('Student/notices'); ?>" class="btn btn-sm btn-info">Know more</a>
					</div>
				</div>
			</aside>
			<div id="applyD" title="List of Applied Programs" style="width: 450px;"></div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="mheader"></h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<div class="modal-body" id="mbody"> 
			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	getProgramNotices();
	checkUserApplication();
	$('#frmLive').validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			$('#loading').css('display', 'block');
			$('#liveBt').attr('disabled', true);
			e.preventDefault();
			var frmLiveData = new FormData($('#frmLive')[0]);
			$.ajax({
				url: baseURL+'Teacher/checkValidCourse',
				type: 'POST',
				data: frmLiveData,
				cache : false,
				processData: false,
				contentType: false,
				async: false,
				success: (res)=>{ 
					$('#frmLive')[0].reset();
					$('#liveBt').removeAttr('disabled');
					$('#loading').css('display', 'none');
					var obj = JSON.parse(res);
					if(obj['status']=='success'){
						window.open(baseURL+'Student/liveClass/?id='+btoa(obj['cid']), '_self');
					}else{
						alert(obj['msg']);
						swal(
						  'Course Code',
						  obj['msg'],
						  obj['status']
						);
					}
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
});
function checkUserApplication()
{
	var dialogBox = '';
	$.ajax({
		url: baseURL+'Student/findUserApply',
		type: 'GET',
		success: (resp)=>{
			var obj = JSON.parse(resp);
			console.log(obj)
			if(obj['status']){
				if(obj['apply_status']){
					dialogBox+='<h5 class="text-danger text-center">You have already applied for this program: '+obj['prog_name']+'</h5>';
				}else{
					dialogBox+='<h5>You have less than 5 minutes to enroll into this program: '+obj['prog_name']+'</h5>';
					dialogBox+='<a href="'+baseURL+'Student/programAdmission/?id='+btoa(obj['prog_id'])+'" class="btn btn-success btn-sm">Enroll Now</a>';
				}
				$('#mbody').html(dialogBox);
				$('#myModal').modal('show');
			}
		}
	});
}
$(document).on('submit', '#frmProgApply', (e)=>{
	e.preventDefault();
	var frmApply = new FormData($('#frmProgApply')[0]);
	$.ajax({
		url: baseURL+'Student/updateApplications',
		type: 'POST',
		data: frmApply,
		contentType: false,
		processData: false,
		success: (resp)=>{
			$('#applyD').dialog( "close" );
			$('#applyD').html("");
			console.log(resp)
			var obj = JSON.parse(resp);
			swal(
			  obj.title,
			  obj.msg,
			  obj.status
			);
		}
	});
});
function getUserData(dtype)
{
	var oricon = '';
	if(dtype=='resume'){
		$('#ricon').html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		$('#mheader').html('User Resume');
		oricon = '<i class="material-icons">verified</i>';
	}else if(dtype=='skills'){
		$('#sicon').html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		$('#mheader').html('User Skills');
		oricon = '<i class="material-icons">bar_chart</i>';
	}
	$.ajax({
		url: baseURL+'Student/getUserValidData',
		type: 'GET',
		data: { dtype: dtype },
		success: (resp)=>{
			console.log(resp)
			$('#mbody').html(resp);
			$('#myModal').modal('show');
			if(dtype=='resume'){
				$('#ricon').html('<i class="material-icons">verified</i>');
			}else if(dtype=='skills'){
				$('#sicon').html('<i class="material-icons">bar_chart</i>');
			}
		}
	});
}
var container = document.querySelector('#jitsi-container');
var api = null;
var meeting_joined = false;
var room_name = '';
var invitation = false;
setInterval(() => {
	$.ajax({
		url: baseURL+'Liveclass/getAllMyInvitation',
		type: 'GET',
		data: {user_id: <?=$userid;?>},
		success: (res)=>{ 			
			var obj = JSON.parse(res);
			if(obj['status'] == 'success'){
				let buttons = "";
				for (let i = 0; i < obj['data'].length; i++) {
					const element = obj['data'][i];
					buttons += '<button class="btn btn-primary btn-block" id="'+element['room_name']+'" ';
					buttons += 'data-room="'+element['room_name']+'" ';
					buttons += 'data-lc_id="'+element['live_class_id']+'" ';
					buttons += 'onClick="joinClass(\''+element['room_name']+'\')"';
					buttons += '>Join Online Class ('+element['c_code']+')</button>';					
				}
				$('div#live-buttons').html(buttons);
				$('#live-button-container').fadeIn(500);
			}	
			if(obj['status'] == 'error'){
				if(meeting_joined == true){
					api.dispose();
					$('div#live-buttons').html("");
					$('div#main-container').fadeIn(500);
					$('#live-button-container').fadeOut(500);
				}else{
					$('div#main-container').fadeIn(500);
					$('div#jitsi-container').fadeOut(500);
				}
			}		
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}, 5000);

function joinClass(roomName){
	let lc_id = $('#'+roomName).data('lc_id');
	$.ajax({
		url: baseURL+'Liveclass/joinMeeting',
		type: 'POST',
		data: {lc_id: lc_id},
		success: (res)=>{ 			
			var obj = JSON.parse(res);
			if(obj['status'] == 'success'){
				console.log(obj['msg']);
				var domain = "meet.jit.si";
				var options = {
					"roomName": roomName,
					"parentNode": container,
					"width": 100+'%',
					"height": 600,
				};
				configOverwrite = {
					prejoinPageEnabled: false
				};
				options['configOverwrite'] = configOverwrite;
				api = new JitsiMeetExternalAPI(domain, options);
				$('div#main-container').fadeOut(500);
				$('div#jitsi-container').fadeIn(500);
				meeting_joined = true;
			}		
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}
</script>
<script src='https://meet.jit.si/external_api.js'></script>