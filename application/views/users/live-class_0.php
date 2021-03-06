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
				<!--<div class="card bg-warning text-white">
					<div class="card-body">
						<form id="frmLive">
							<input type="text" name="course_code" id="course_code" class="form-control text-white" placeholder="Enter Course code.." required="true">
							<button class="btn bg-dark btn-block" id="liveBt" type="submit">Start Live Class</button>
						</form>
					</div>
				</div>-->
				<button class="btn btn-warning btn-block">Doubt Box</button>
				<div class="card bg-info text-white">
					<div class="card-body">
						<p>
							<?php
								$count_sh = count($sch_class);
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
								
							?>
						</p>
						
						<button class="btn bg-success btn-block" id="start">Start Class Now</button>
						<button class="btn bg-danger btn-block" id="stop" style="display:none">Stop Online Class</button>
						<div style="height:40vh;">
							<div class="student_ticker mt-3">
								<ul class="list-group">
									<?php foreach($pstud as $srow){ 
										echo '<li class="list-group-item">'.$srow->name.'</li>';
									} ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</aside>

		</div>
	</div>
</div>
<script>
$(document).ready(function() {
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

var startBtn = document.querySelector('#start');
var stopBtn = document.querySelector('#stop');
var container = document.querySelector('#jitsi-container');
var api = null;

startBtn.addEventListener('click', () => {
    var domain = "meet.jit.si";
    var options = {
        "roomName": "foo-bar-c8cfdb0d-135a-4462-a05b-36575c3ef591",
        "parentNode": container,
        "width": 100+'%',
        "height": 600,
    };
    api = new JitsiMeetExternalAPI(domain, options);
	$('#start').fadeOut(2000);
	$('#stop').fadeIn(4000);
});
stopBtn.addEventListener('click', () => {
	$('#jitsi-container').fadeOut(2000);
    $('#stop').fadeOut(2000);
	$('#start').fadeIn(4000);
});

</script>
<script src='http://meet.techmagnox.com/external_api.js'></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="<?php echo base_url();?>assets/vendor/video/test.js"></script>
<script src="<?php echo base_url();?>assets/vendor/video/main.js"></script>