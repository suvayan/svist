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
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-md-8">
				<div class="card">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Assignments : <?php echo '<u>'.$cadetails[0]->title.'</u>'; ?></h4>
					  </div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
								<?php 
									echo '<h5>Added on '.date('j M Y',strtotime($cadetails[0]->add_date)).'<br>Full Marks: '.$cadetails[0]->marks.'</h5>'; 
									echo '<h5>Submission Date: '.date('j M Y',strtotime($cadetails[0]->tdate)).'<br>Deadline: '.date('j M Y',strtotime($cadetails[0]->deadline)).'</h5>';
									if($cadetails[0]->details!=""){
										echo trim($cadetails[0]->details);
									}
								?>
							</div>
							<div class="col-md-12 text-center">
								<div class="row">
								<?php 
									foreach(${'cafiles'} as $frow){
										$isrc = '';
										$caid = $frow->sl;
										$rtype = trim($frow->file_type);
										$ftype = trim($frow->file_ext);
										if($ftype=='pdf'){
											$isrc = 'fa-file-pdf-o';
										}else if($ftype=='doc' || $ftype=='docx'){
											$isrc = 'fa-file-word-o';
										}else if($ftype=='xls' || $ftype=='xlsx'){
											$isrc = 'fa-file-excel-o';
										}else if($ftype=='jpg'){
											$isrc = 'fa-file-image-o';
										}else{
											$isrc = 'fa-file';
										}
										echo '<div class="col-md-3 col-sm-3 col-xs-3">';
										if($rtype=='lk'){
											//echo '<a href="'.$frow->file_name.'" target="_blank"><img src="'.base_url().'assets/img/icons/link.png" style="width:30%;"> View Link</a>';
											echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$caid.'" data-notify="container">
													<i class="material-icons" data-notify="icon">link</i>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.$frow->file_name.'" target="_blank">View Link</a></span>
												</div>';
										}else if($rtype=='fl'){
											//echo '<a href="'.base_url().'uploads/cassignments/'.$frow->file_name.'" target="_blank"><img src="'.base_url().'assets/img/icons/'.$isrc.'.png" style="width:30%;"> Download</a>';
											if(file_exists('./uploads/cassignments/'.$frow->file_name)){
												echo '<div class="alert alert-primary alert-with-icon mt-3" id="alert_'.$caid.'" data-notify="container">
													<i class="fa '.$isrc.'" data-notify="icon"></i>
													<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
													<span data-notify="message"><a href="'.base_url().'uploads/cassignments/'.$frow->file_name.'" target="_blank"> Download</a></span>
												</div>';
											}
										}
										echo '</div>';
									} 
								?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Assignment Submission</h4>
					  </div>
					</div>
					<div class="card-body" id="assgn_submit">
					
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<a href="<?php echo base_url().'Student/viewProgram/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm " style="margin-top:30px;">Back to programs</a>
				<a href="<?php echo base_url().'Student/courseDetails/?cid='.base64_encode($cid).'&prog='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Back to Course Details</a>
				<div class="card">
					<div class="card-header card-header-info">
						<h4 class="card-title">Notices</h4>
					</div>
					<div class="card-body">
						<div class="notice_ticker" style="height:30vh;">
							<ul class="list-group" id="notc_details">
							
							</ul>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="modal fade" id="casModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Create Assignment Submission</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<form class="form" id="frmAssgnFiles">
				<div class="form-group">
					<input type="hidden" value="<?php echo $cadetails[0]->sl; ?>" name="ass_sl" id="ass_sl">
					<label for="rtitle" class="text-dark">Assignment Submission for</label>
					<?php echo $cadetails[0]->title; ?>
				</div>
				<div class="form group">
					<label for="rdetails" class="text-dark">Description</label>
					<textarea class="form-control w-100" cols="80" rows="5" name="rdetails" id="rdetails" placeholder="**not more than 200 letters" maxLength="200"></textarea>
				</div>
				<div class="form group">
					<button type="submit" class="btn btn-link pull-right">Save</button>
					<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
		
	  </div>
	</div>
</div>
<div class="modal fade" id="casfModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Add Assignment Solution Files</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<div class="modal-body">
			<div class="text-center" id="yfl_button">
				<button type="button" onClick="addMore('fl');" class="btn btn-sm btn-info"><i class="material-icons">add</i> Files</button>
			</div>
			<form class="form" method="POST" enctype="multipart/form-data" id="frmAssgnSubFiles">
				<div class="form-group">
					<input type="hidden" value="" name="fassgn_id" id="fassgn_id">
					<input type="hidden" value="0" name="afcount" id="afcount">
				</div>
				
				<span id="cal_fields"></span>

				<div class="form group">
					<button type="submit" class="btn btn-link pull-right">Upload</button>
					<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
		
	  </div>
	</div>
</div>
<!------------------->
<script src="<?php echo base_url().'assets/js/cassignments.js'; ?>"></script>
<script>
$(document).ready(function() {
	getProgramNotices();
	getAssignmentSubmission('<?php echo $cadetails[0]->sl; ?>');
});

function getAssignmentSubmission(caid)
{
	$.ajax({
		url: baseURL+'Student/getAssignmentSolutions',
		type: 'GET',
		data: {id:caid},
		success: (resp)=>{
			$('#assgn_submit').html(resp);
		},
		error: (errors)=>{
			
		}
	});
}
function openAssgnFileModel(assgn_sl)
{
	$('#fassgn_id').val(assgn_sl);
	$('#casfModel').modal('show');
}
$('#frmAssgnFiles').on('submit', (e)=>{
	e.preventDefault();
	$('#loading').css('display', 'block');
	var frmDataSub = new FormData($('#frmAssgnFiles')[0]);
	$.ajax({
		url: baseURL+'Student/createAssignmentSubmission',
		type: 'POST',
		data: frmDataSub,
		cache : false,
		processData: false,
		contentType: false,
		//enctype: 'multipart/form-data',
		async: false,
		success: (res)=>{ 
			$('#casModal').modal('hide');
			$('#loading').hide();
			var obj = JSON.parse(res);
			Swal.fire(
			  'Assignment',
			  obj.msg,
			  obj.status
			).then(result=>{
				getAssignmentSubmission('<?php echo $cadetails[0]->sl; ?>');
			})
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
});
$('#frmAssgnSubFiles').on('submit', (e)=>{
	$('#loading').css('display', 'block');
	e.preventDefault();
	var frmAssgnFile = new FormData($('#frmAssgnSubFiles')[0]);
	$.ajax({
		url: baseURL+'Student/cuCAssignmentFiles',
		type: 'POST',
		data: frmAssgnFile,
		cache : false,
		processData: false,
		contentType: false,
		enctype: 'multipart/form-data',
		async: false,
		success: (res)=>{ 
			$('#casfModel').modal('hide');
			$('#loading').hide();
			Swal.fire(
			  'Assignment File/s',
			  'has been uploaded',
			  'success'
			).then(result=>{
				getAssignmentSubmission('<?php echo $cadetails[0]->sl; ?>');
			})
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
});
</script>