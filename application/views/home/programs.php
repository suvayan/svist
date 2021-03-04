<div class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-8 mx-auto">
				<div class="card mt-1">
				<div class="card-body">
					<div id="accordion" role="tablist">
						<div class="card-collapse">
						  <div class="card-header" role="tab" id="headingOne" style="padding: 5px 10px 5px 0 !important;">
							<h5 class="mb-0 text-center">
							  <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
								Search Filters
								<i class="material-icons">keyboard_arrow_down</i>
							  </a>
							</h5>
						  </div>
						  <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" class="form-control" name="ptitle" id="ptitle" placeholder="Search by title" />
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<select class="selectpicker" name="pcat" id="pcat" data-style="select-with-transition" title="Search by category">
												<option value="">All</option>
												<option value="Long Term">Long Term</option>
												<option value="Short Term">Short Term</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						  </div>
						</div>
					</div>
				</div>
				</div>
			</div>
			<div class="col-12">
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title text-center">All Programs</h3>
					</div>
					<div class="card-body">
						<div class="progress" id="dt_progress" style="height:20px !important; display:none;">
							<div class="progress-bar progress-bar-striped progress-bar-animated" id="dt_progress_bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%">Loading...</div>
						</div>
						<div id="dt_list">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
	$(document).ready(function() {
		getallfilterdata();
	});
	//title, company, location, job_type, job_apply
	function getallfilterdata()
	{
		$('#dt_progress').css('display', 'block');
		$('#dt_list').html("");
		var title = $('#ptitle').val();
		var pcat = $('#pcat').val();
		var papply = $('#papply').val();
		$.ajax({
			url: baseURL+'Login/getProgramsFilter',
			type: 'POST',
			data: {
				title: title,
				pcat: pcat
				/*,
				papply: papply,*/
			},
			success: (res)=>{
				$('#dt_progress').css('display', 'none');
				$('#dt_list').html(res);
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	}
	
	$('.form-control').keyup(function() {
		getallfilterdata();
	});
	$('.selectpicker').change(function() {
		getallfilterdata();
	});
	
	/*function userApplyProgram(stud, prof, prog)
	{
		if(stud=='1' && prof==''){
		  Swal.fire({
			  title: 'Apply',
			  text: "Do you want to enroll as a Student?",
			  type: 'question',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, please!'
			}).then((result) => {
			  if (result.value) {
				sendProgRoleData('Student', prog);
			  }
			});
		}else if(stud=='' && prof=='1'){
		  Swal.fire({
			  title: 'Apply',
			  text: "Do you want to apply as a role of a Teacher?",
			  type: 'question',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, please!'
			}).then((result) => {
			  if (result.value) {
				sendProgRoleData('Teacher', prog);
			  }
			});
		}else if(stud=='1' && prof=='1'){
		  Swal.fire({
			  title: 'Apply',
			  html: '<form><h4>Select the available option below</h4><div class="checkbox-radios"><div class="form-check form-check-inline"><label class="form-check-label"><input class="form-check-input" type="radio" value="Teacher" name="roleType" id="roleProf" required="true"> Teacher<span class="form-check-sign"><span class="check"></span></span></label></div><div class="form-check form-check-inline"><label class="form-check-label"><input class="form-check-input" type="radio" value="Student" name="roleType" id="roleStud" required="true"> Student<span class="form-check-sign"><span class="check"></span></span></label></div></div></form>',
			  type: 'question',
			  showCloseButton: true,
			  showCancelButton: true,
			  cancelButtonColor: '#d33',
			  cancelButtonText: 'Cancel',
			  confirmButtonColor: '#3085d6',
			  confirmButtonText: 'Save'
			}).then((result) => {
				if((result.value)){
					var role = $("input[type='radio'][name='roleType']:checked"). val();
					if(role!=null){
						sendProgRoleData(role, prog);
					}
				}
			});
		}
	}
	function sendProgRoleData(role, prog)
	{
		$.ajax({
			url: baseURL+'Login/applyRoleToPRogram',
			type: 'POST',
			data: {role: role, pid: prog},
			success: (res)=>{
				if(res)
				{
					$('#act_'+cid).removeClass(' btn-danger');
					$('#act_'+cid).addClass(' btn-info');
					$('#act_'+cid).html('Applied');
					Swal.fire({
					  title: 'Success',
					  text: 'Your application for this program has been made.',
					  type: 'success'
					})
				}else{
					Swal.fire(
					  'Failed!',
					  'Your role as a '+role+' failed. Server error.',
					  'error'
					)
				}
			},
			error: (errors)=>{
				console.log(errors);
			}
		})
	}*/
  </script>
</div>