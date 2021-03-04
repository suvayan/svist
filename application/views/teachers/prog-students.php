<style>
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
				<div class="card ">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title"><?php echo $prog[0]->title.' ('.$prog[0]->code.')'; ?></h4>
					  </div>
					  <a href="<?php echo base_url().'Teacher/viewProgram/?id=',base64_encode($prog[0]->id); ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> Back to Program Details</a>
					</div>
					<div class="card-body">
						<h6 style="font-variant:normal">
							
							<?php
								if($prog[0]->facebook!=""){
									echo '<a href="'.$prog[0]->facebook.'" target="_blank" class="btn btn-just-icon btn-link btn-facebook"><i class="fa fa-facebook"> </i></a>';
								}
								if($prog[0]->twitter!=""){
									echo '<a href="'.$prog[0]->twitter.'" target="_blank" class="btn btn-just-icon btn-link btn-twitter"><i class="fa fa-twitter"> </i></a>';
								}
								if($prog[0]->linkedin!=""){
									echo '<a href="'.$prog[0]->linkedin.'" target="_blank" class="btn btn-just-icon btn-link btn-linkedin"><i class="fa fa-linkedin"> </i></a>';
								}
								$type = trim($prog[0]->ptype);
								$category = trim($prog[0]->category);
								if($type!=""){
									echo $type.' Program';
								}
								if($category!=""){
									echo ', Category : '.$category.', ';
								}
								$dur = intval(trim($prog[0]->duration));
								echo 'Duration: '.$dur.' '.trim($prog[0]->dtype).'(s), ';
								if(trim($prog[0]->feetype)=='paid'){
									echo 'Total Fees: '.trim($prog[0]->total_fee).',   ';
								}
								if(trim($prog[0]->total_credit)!=""){
									echo 'Total Credit: '.$prog[0]->total_credit;
								}
							?>
						</h6>
					</div>
				</div>
				
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Student
							<button class="btn btn-sm btn-primary pull-right btn-link" onClick="getInviteModal(<?php echo $prog[0]->id; ?>)">Invite Now</button>
							<a href="<?php echo base_url('Teacher/invitation'); ?>" class="btn btn-sm btn-primary pull-right btn-link">View Invitations</a>
							<?php
								if($prog[0]->type!='1'){
									echo '<a href="'.base_url('Teacher/requestLearning').'" class="btn btn-sm btn-primary pull-right btn-link">View Request</a>';
								}else if($prog[0]->type!='2'){
									echo '<a href="'.base_url('Teacher/manageAdmission').'" class="btn btn-sm btn-primary pull-right btn-link">View Request</a>';
								}
							?>
						</h4>
					</div>
					<div class="card-body">
						<div class="material-datatables">
							<table class="table table-striped table-no-bordered table-hover dtinsstrm" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="5%">Sl.</th>
										<th width="60%" colspan="2">Name</th>
										<th width="20%">Email</th>
										<th width="15%">Phone</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; foreach($pstud as $srow){ ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td width="9%"><?php echo '<img src="'.base_url().$srow->photo_sm.'" onerror="this.src=`'.base_url('assets/img/default-avatar.png').'`" class="avatar-dp mr-2" />'; ?></td>
											<td><?php echo '<div class="td-name">'.$srow->name.'</div>'; ?></td>
											<td><?php echo $srow->email; ?></td>
											<td><?php echo $srow->phone; ?></td>
										</tr>
									<?php $i++; } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
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
					<div class="card-footer">
						<button class="btn btn-info btn-sm pull-right">Know More</button>
					</div>
				</div>
				<a href="<?php echo base_url().'Teacher/progTeachers/?id='.base64_encode($prog_id); ?>" class="btn btn-primary btn-block btn-sm ">Teachers</a>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="noticeM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Invitation Form</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmInvite" enctype="multipart/form-data">
				<div class="modal-body">
				  <div class="form-group mb-0">
					<label for="inv_email" class="text-dark">Email*</label>
					<input type="hidden" name="inv_prog" id="inv_prog" value="<?php echo $prog_id; ?>"/>
					<input type="hidden" name="inv_role" id="inv_role" value="Student"/>
					<input type="hidden" name="inv_exist" id="inv_exist" value="0"/>
					<input type="text" class="form-control" list="inv_users" name="inv_email" id="inv_email" required="true" email="true">
					<datalist id="inv_users">
					
					</datalist>
				  </div>	
				  <div class="form-group mb-0">
					<label for="inv_fname" class="text-dark">Firstname*</label>
					<input type="text" class="form-control" name="inv_fname" id="inv_fname" required="true" minLength="2">
				  </div>
				  <div class="form-group mb-0">
					<label for="inv_lname" class="text-dark">Lastname*</label>
					<input type="text" class="form-control" name="inv_lname" id="inv_lname" required="true">
				  </div>
				  <div class="form-group mb-0">
					<label for="inv_phone" class="text-dark">Phone*</label>
					<input type="text" class="form-control" name="inv_phone" id="inv_phone" required="true" minLength="10" maxLength="10" digits="true">
				  </div>
				  <div class="form-group mb-0">
					<label for="semid" class="text-dark">Semester*</label>
					<select class="selectpicker" data-style="select-with-transition" data-title="Select Semester*" name="semid" id="semid">
						<?php
							if(!empty($sems)){
								$csems = count($sems);
								if($csems==1){
									echo '<option value="'.$sems[0]->id.'" selected>'.$sems[0]->title.'</option>';
								}else{
									foreach($sems as $ssow){
										if($cd[0]->sem_id==$ssow->id){
											echo '<option value="'.$ssow->id.'" selected>'.$ssow->title.'</option>';
										}else{
											echo '<option value="'.$ssow->id.'">'.$ssow->title.'</option>';
										}
									}
								}
							}
						?>
					</select>
				  </div>
				  <div class="form-group mb-0">
					<label for="ay_id" class="text-dark">Academic Year*</label>
					<select class="selectpicker" data-style="select-with-transition" data-title="Select Academic Year*" name="ay_id" id="ay_id">
						<?php
							if(!empty($ayr)){
								foreach($ayr as $ayow){
									echo '<option value="'.$ayow->sl.'">'.$ayow->yearnm.'</option>';
								}
							}
						?>
					</select>
				  </div>
				  <div class="form-group mb-0">
					<label for="enroll" class="text-dark">Enrollment No.</label>
					<input type="text" class="form-control" name="enroll" id="enroll">
				  </div>
				  <div class="form-group mb-0">
					<label for="roll" class="text-dark">Roll No.</label>
					<input type="text" class="form-control" name="roll" id="roll">
				  </div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" class="btn btn-link">Invite</button>
				  
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		getProgramNotices();
		
		$('#frmInvite').validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmInviteData = new FormData($('#frmInvite')[0]);
				$.ajax({
					url: baseURL+'Teacher/inviteENEUser',
					type: 'POST',
					data: frmInviteData,
					cache : false,
					processData: false,
					contentType: false,
					enctype: 'multipart/form-data',
					async: false,
					success: (res)=>{ 
						$('#noticeM').modal('hide');
						$('#frmInvite')[0].reset();
						$('#loading').css('display', 'none');
						var obj = JSON.parse(res);
						//console.log(obj)
						swal(
						  'Invitation',
						  obj.msg,
						  obj.status
						).then(result=>{
							getProgramNotices();
						});
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
	});
	
	function getInviteModal(prog_id)
	{
		$('#frmInvite')[0].reset();
		var uList = '';
		//$('#noticeM').modal('show');
		$('#loading').css('display', 'block');
		$.ajax({
			url:baseURL+'Teacher/getUserList',
			type: 'GET',
			data: { type: 'Student', prog: prog_id },
			success: (resp)=>{
				//console.log(res);
				var obj = JSON.parse(resp);
				if(obj!=null){
					$.each(obj, (i, val)=>{
						uList+='<option value="'+(val['email']).trim()+'" id="'+(val['first_name']).trim()+'+'+(val['last_name']).trim()+'+'+(val['phone']).trim()+'+1">';
					});
				}
				$('#loading').css('display', 'none');
				$('#inv_users').html(uList);
				$('#noticeM').modal('show');
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
	
	$('#inv_email').on('input', function() {
		var userText = $(this).val();

		$("#inv_users").find("option").each(function() {
		  if ($(this).val() == userText) {
			  var str = $(this).attr('id').split('+');
			  $('#inv_fname').val(str[0]);
			  $('#inv_lname').val(str[1]);
			  $('#inv_phone').val(str[2]);
			  $('#inv_exist').val(str[3]);
		  }
		})
	});
</script>