<style>
.form-error{
	color:red;
}
.dropdown-toggle::after {
    display:none;
}
#ytbody iframe: {
	width:100% !important;
}
.loader {
  background-color: #ffffff;
  opacity:0.5;
  position: fixed;
  z-index: 999999;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
}

.loader img {
  position: absolute;
  top: 50%;
  left: 50%;
  text-align: center;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
</style>
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info card-header-text">
					  <div class="card-text">
						<h4 class="card-title"><?php echo '<a href="'.base_url().'Student/viewProgram/?id='.base64_encode($prog[0]->id).'"><u>'.$prog[0]->title.' ('.$prog[0]->code.')</u></a> > '.$cdetails[0]->title.' ('.$cdetails[0]->c_code.')'; ?></h4>
					  </div>
					</div>
					<div class="card-body">
						<button type="button" id="home" onClick="getBtnContent(<?php echo trim("'home', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);" class="btn btn-sm btn-default"><i class="material-icons">home</i></button>
						<button type="button" id="lec" onClick="getBtnContent(<?php echo trim("'lectures', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);" class="btn btn-sm btn-default"><i class="material-icons">description</i> Lectures</button>
						<button type="button" id="resc" onClick="getBtnContent(<?php echo trim("'resources', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);" class="btn btn-sm btn-default"><i class="material-icons">storage</i> Resources</button>
						<button type="button" id="assign" onClick="getBtnContent(<?php echo trim("'assignments', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);" class="btn btn-sm btn-default"><i class="material-icons">library_books</i> Assignments</button>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'grade', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">grading</i> Grades</button>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'quiz', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">assessment</i> Quiz</button>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'schedule', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">schedule</i> Schedule</button>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'doubts', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">help</i> Doubts</button>
						<a href="<?php echo base_url().'Student/liveClass/?id='.base64_encode($cdetails[0]->id); ?>" class="btn btn-sm btn-default"><i class="material-icons">class</i> Live Class</a>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'teachers', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">person</i> Teachers</button>
						<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php echo trim("'attendance', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">report</i> Attendance</button>
						<a href="<?php echo base_url('Student/message'); ?>" class="btn btn-sm btn-default"><i class="material-icons">email</i></a>
						<!--<button type="button" class="btn btn-sm btn-default" onClick="getBtnContent(<?php //echo trim("'dummy', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);"><i class="material-icons">settings</i></button>-->
					</div>
					<div class="progress" id="progress" style="display:none;">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="progbra"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container" id="btn_content">
	
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function(){
	getBtnContent(<?php echo trim("'home', ".$cdetails[0]->id.", ".$prog[0]->id); ?>);
});


function getBtnContent(page, cid, prog)
{
	$('#btn_content').html("", 1000);
	$('#progbra').css('width', '0');
	$('#progress').show();
	$.ajax({
		xhr: function () {
			var xhr = new window.XMLHttpRequest();
			xhr.upload.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					console.log(percentComplete);
					$('#progbra').css({
						width: percentComplete * 100 + '%'
					});
					if (percentComplete === 1) {
						$('#progbra').addClass('hide');
					}
				}
			}, false);
			xhr.addEventListener("progress", function (evt) {
				if (evt.lengthComputable) {
					var percentComplete = evt.loaded / evt.total;
					console.log(percentComplete);
					$('#progbra').css({
						width: percentComplete * 100 + '%'
					});
				}
			}, false);
			return xhr;
		},
		type: 'GET',
		url: baseURL+"Student/getMenuContent",
		data: {page: page, cid: cid, prog: prog},
		success: function(data){
			$('#progress').fadeOut(2000);
			$('#btn_content').html(data);
			$('#btn_content').fadeIn(2000);
		}
	});
}
</script>