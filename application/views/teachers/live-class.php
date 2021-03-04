<style>
#course_code::placeholder {
	color: white;
}
</style>
<div class="content">
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-8">
				<div id="jitsi-container" style="min-height:400px"></div>
			</div>
			
			<aside class="col-md-4">
				<div class="card bg-info text-white">
					<div class="card-body">
						<p>
							<?php
								/*$count_sh = count($sch_class);
								echo '<strong>Program: </strong>'.$progcourse[0]->program_title.'<br>';
								echo '<strong>Course: </strong>'.$progcourse[0]->course_title.'<br>';
								echo '<div class="togglebutton">
									<label class="text-dark">
										Schecduled Class?
										<input type="checkbox" name="schNotify" id="schNotify" checked="true" value="1" '.(($count_sh<=0)? 'checked': '').'>
										<span class="toggle"></span>
									</label>
								</div>';
								if($count_sh>0){
									echo '<select class="selectpicker" data-style="select-with-transition" title="Select a schedule class*">';
									foreach($sch_class as $shrow){ 
										echo '<option value="'.$shrow->id.'">'.$shrow->class_title.' ('.$shrow->class_type.')</option>';
									}
									echo '</select>';
								}
								*/
							?>
						</p>
						
						<button class="btn bg-success btn-block" id="start">Start Class Now</button>
						<button class="btn bg-danger btn-block" id="stop" style="display:none">Stop Online Class</button>
						<div class="student_ticker mt-3" style="height:40vh; overflow-y:scroll;">
							<ul class="list-group">
								<?php foreach($pstud as $srow){ 
									echo '<li class="list-group-item">';
									echo '<input type="checkbox" class="form-check-input" name="students" value="'.$srow->id.'" checked>'.$srow->name.' <i id="tick-'.$srow->id.'" class="material-icons tick d-none">done</i>';
									echo '</li>';
								} ?>
							</ul>
						</div>
						<button class="btn bg-primary btn-block" id="invite" style="display:none"  onClick="inviteStudents()">Invite Students</button>
						<button class="btn bg-primary btn-block" id="attendance" style="display:none"  onClick="takeAttendance()">Take Attendance</button>
					</div>
				</div>
			</aside>

		</div>
	</div>
</div>
<script>
$(document).ready(function() {
	
	//const ps = new PerfectScrollbar('.student_ticker');


	//$('.student_ticker').easyTicker();
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
						window.open(baseURL+'Teacher/liveClass/?id='+btoa(obj['cid']), '_self');
					}else{
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

var startBtn = document.querySelector('#start');
var stopBtn = document.querySelector('#stop');
var container = document.querySelector('#jitsi-container');
var api = null;
var live_id = null;
var invited = false;
var cid = <?php echo $cid; ?>;

startBtn.addEventListener('click', () => {
	var domain = "meet.jit.si";
	var time = new Date().getTime();
    var room = 'magnox' + time;
    var options = {
        "roomName": room,
        "parentNode": container,
        "width": 100+'%',
        "height": 600,
	};
	configOverwrite = {
        prejoinPageEnabled: false
    };

    options['configOverwrite'] = configOverwrite;

	$.ajax({
		url: baseURL+'Liveclass/startLiveClass',
		type: 'POST',
		data: {course_id: <?=$cid;?>, room_name: room},
		success: (res)=>{ 			
			var obj = JSON.parse(res);
			if(obj['status'] ==  'success'){
				//console.log(obj['room']);
				live_id = obj['live_id'];
				options['roomName'] = obj['room'];
				api = new JitsiMeetExternalAPI(domain, options);
				$('#jitsi-container').fadeIn(2000);
				$('#start').fadeOut(2000);
				$('#stop').fadeIn(4000);
				$('#invite').fadeIn(500);
				$('#attendance').fadeIn(500);
			}
			//console.log(obj['msg']);			
		},
		error: (errors)=>{
			console.log(errors);
		}
	});	
});
stopBtn.addEventListener('click', () => {
	api.dispose();
	$.ajax({
		url: baseURL+'Liveclass/stopLiveClass',
		type: 'POST',
		data: {course_id: <?=$cid;?>},
		success: (res)=>{ 			
			var obj = JSON.parse(res);
			live_id = null;
			//console.log(obj['msg']);			
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
	$('#jitsi-container').fadeOut(2000);
    $('#stop').fadeOut(2000);
	$('#start').fadeIn(4000);
	$('#invite').fadeOut(500);
	$('#attendance').fadeOut(500);
	$('i.tick').addClass('d-none');
	invited = false;
});



/*setInterval(() => {
	if(live_id != null){
		$.ajax({
			url: baseURL+'Liveclass/getInvitationStatus',
			type: 'POST',
			data: {live_id: live_id},
			success: (res)=>{ 			
				var obj = JSON.parse(res);
				obj.data.forEach(element => {
					if(element.joined == 't'){
						$('i#tick-'+element.student_id).removeClass('d-none');
					}else{
						$('i#tick-'+element.student_id).addClass('d-none');
					}
				});
				//console.log(obj);		
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
}, 5000);*/

function inviteStudents(){
	var students = [];
	$.each($('input[name="students"]:checked'), function(){
		students.push($(this).val());
	})
	if(students.length > 0){		
		$.ajax({
			url: baseURL+'Liveclass/inviteStudents',
			type: 'POST',
			data: { cid: cid, live_id: live_id, students: students},
			success: (res)=>{ 			
				var obj = JSON.parse(res);
				console.log(obj)
				if(obj['status'] == 'success'){
					
					//alert();
					$.notify({icon:"add_alert",message:obj['msg']},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
					invited = true;
				}			
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}else{
		$.notify({icon:"add_alert",message:"Please select student(s) to invite"},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
	}
	
}

function takeAttendance(){
	$.ajax({
		url: baseURL+'Liveclass/takeAttendance',
		type: 'POST',
		data: {live_id: live_id, course_id: <?=$cid;?>},
		success: (res)=>{ 			
			var obj = JSON.parse(res);	
			console.log(res);		
			//alert(obj['msg']);		
			$.notify({icon:"add_alert",message:obj['msg']},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})			
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}

</script>
<script src='https://meet.jit.si/external_api.js'></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="<?php echo base_url();?>assets/vendor/video/test.js"></script>
<script src="<?php echo base_url();?>assets/vendor/video/main.js"></script>