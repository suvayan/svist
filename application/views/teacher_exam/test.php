<style>
.card[class*=bg-], .card[class*=bg-] .card-title, .card[class*=bg-] .card-title a, .card[class*=bg-] .icon i, .card [class*=card-header-], .card [class*=card-header-] .card-title, .card [class*=card-header-] .card-title a, .card [class*=card-header-] .icon i {
    color: #000 !important;
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
		<div class="card">
			<div class="card-header card-header-info">
				<h3 class="card-title">Tests
				<button type="button" onClick="testModal('add', '')" class="btn btn-sm btn-primary pull-right">Create Test</button>
				<div class="form-group m-0 d-fex pull-right">
					<select name="sh_prog" id="sh_prog" onChange="getProgCourses(this.value, 'sh_', ''); getFilteredTest();" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<option value="">All Program</option>
						<?php 
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
						?>
					</select>
					<select name="sh_course" id="sh_course" class="selectpicker" data-style="select-with-transition" title="Select an course*" required="true" onChange="getFilteredTest();">

					</select>
				</div>
				</h3>
			</div>
			<div class="card-body">
				<div class="progress-bar progress-bar-striped progress-bar-animated" id="qb_progress" style="width:100%; display:none;">Loading...</div>
				<div id="test_list">
				
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="ttModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="tt_head"></h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmTest" enctype="multipart/form-data">
				<div class="modal-body">
				  <div class="form-group mb-0">
					<select name="tt_prog" id="tt_prog" onchange="getProgCourses(this.value, 'tt_', '');" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<?php 
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group mb-0">
					<select name="tt_course" id="tt_course" class="selectpicker" data-style="select-with-transition" required="true">

					</select>
				</div>
				  <div class="form-group mb-0">
					<label for="tt_title" class="text-dark">Title</label>
					<input type="text" class="form-control" name="tt_title" id="tt_title" required="true">
					<input type="hidden" name="tt_id" id="tt_id" value="0">
					<input type="hidden" name="program" id="program" value="0">
					<input type="hidden" name="course" id="course" value="0">
				  </div>				  
				  <div class="form-group mb-0">
					<label for="tt_details" class="text-dark">Instruction/Details</label><br>
					<textarea class="form-control" name="tt_details" id="tt_details" required="true"></textarea>
				  </div>
				  <div class="row">
					<div class="col-sm-6">
						<label for="tt_time" class="text-dark mb-0">Time (in minutes)</label>
						<input type="number" class="form-control" name="tt_time" id="tt_time" required="true">
					</div>
					<div class="col-sm-6">
						<label for="tt_marks" class="text-dark mb-0">Marks</label>
						<input type="number" class="form-control" name="tt_marks" id="tt_marks" required="true">
					</div>
				  </div>
				</div>
				<div class="modal-footer">
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" id="tt_btn" class="btn btn-link">Save</button>
				  
				  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="tpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="tp_head">Do you want to publish this test?</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmTestPub" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="form group">
						<label>Test name: </label><span id="tp_name"></span><br>
						<label>Test duration: </label><span id="tp_dur"></span>
						<input type="hidden" name="tid" id="tid" value=""/>
					</div>
					<div class="form-group">
						<select class="selectpicker" data-style="select-with-transition" title="Launch Type" name="launch" id="launch" required="true">
							<option value="1">Anytime</option>
							<option value="2">Particular Time</option>
							<option value="3">Start and End time</option>
						</select>
					</div>
					<div class="row">
						<div class="col-sm-6" id="sdatetm" style="display:none;">
							<div class="form-group">
								<label for="sdatetime">Start Date-time</label>
								<input type="datetime-local" name="sdatetime" id="sdatetime" class="form-control" required="true"/>
							</div>
						</div>
						<div class="col-sm-6" id="edatetm" style="display:none;">
							<div class="form-group">
								<label for="edatetime">End Date-time</label>
								<input type="datetime-local" name="edatetime" id="edatetime" class="form-control" required="true"/>
							</div>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" id="tp_btn" class="btn btn-link">Publish Now</button>
				  
				  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function(){
		getAllTestList('', '');
		$('#tt_details').summernote();
		$("form#frmTest").validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmTTData = new FormData($('#frmTest')[0]);
				$.ajax({
					url: baseURL+'Exam/cuTest',
					type: 'POST',
					data: frmTTData,
					cache : false,
					processData: false,
					contentType: false,
					async: false,
					success: (resp)=>{ 
						$('#ttModal').modal('hide');
						$('#frmTest')[0].reset();
						$('#loading').css('display', 'none');
						var obj = JSON.parse(resp);
						if(obj['status']=='success'){
							getAllTestList('', '');
						}
						$.notify({icon:"add_alert",message:'The Test '+obj['msg']},{type:obj['status'],timer:3e3,placement:{from:'top',align:'right'}})
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
		$('#frmTestPub').validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmData = new FormData($('#frmTestPub')[0]);
				$.ajax({
					url: baseURL+'Exam/publishTest',
					type: 'POST',
					data: frmData,
					cache : false,
					processData: false,
					contentType: false,
					async: false,
					success: (resp)=>{ 
						$('#tpModal').modal('hide');
						$('#frmTestPub')[0].reset();
						$('#loading').css('display', 'none');
						if(resp)
						{
							swal({
								title: 'Successful!',
								text: 'The Test has been published.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							}).then((result)=>{
								getAllTestList('', '');
							})
						}else{
							swal({
								title: 'Failed!',
								text: 'Something went worng.',
								type: 'warning',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							})
						}
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
	});
	
	function getFilteredTest()
	{
		var prog_id = $('#sh_prog').val();
		var course_id = $('#sh_course').val();
		if(prog_id!=''){
			getAllTestList(prog_id, course_id);
		}
	}
	function getAllTestList(pid, cid)
	{
		$('#test_list').html();
		$('#qb_progress').show();
		$.ajax({
			url: baseURL+'Exam/getAllUserTests',
			type: 'GET',
			data: {pid: pid, cid: cid},
			async: false,
			success: (data)=>{
				$('#qb_progress').hide();
				$('#test_list').html(data);
			}
		})
	}
	
	function testModal(func, qid)
	{
		if(func=='add'){
			$('#frmTest')[0].reset();
			$('#tt_btn').html('Save');
			$('#tt_id').val(0);
			$('#tt_head').html('Add Test');
			$('#ttModal').modal('show');
		}else{
			$('#tt_btn').html('Update');
			$('#tt_head').html('Update Test');
			$('#tt_id').val(qid);
			$('#loading').css('display', 'block');
			$.ajax({
				url: baseURL+'Exam/getTestByID',
				type: 'GET',
				data: {
					id: qid
				},
				success: (data)=>{
					$('#loading').hide();
					var res = JSON.parse(data);
					$('#tt_title').val(res[0].title);
					$('#tt_prog').val(res[0].cat_id).selectpicker('refresh');
					getProgCourses(res[0].cat_id, 'tt_', res[0].scat_id);
					$('#tt_course').val(res[0].scat_id).selectpicker('refresh');
					$('#tt_details').summernote('code', res[0].details);
					$('#tt_time').val(res[0].duration);
					$('#tt_marks').val(res[0].marks);
					$('#ttModal').modal('show');
				}
			});
		}
	}
	
	function getProgCourses(prog_sl, cp_id, cid)
	{
		var crList='';
		if(cp_id=='sh_'){
			crList+='<option value="">Select Courses</option>';
		}
		crList+='<option value="0">All Courses</option>';
		$.ajax({
			url: baseURL+'Teacher/getCoursebyProg',
			type: 'GET',
			data: {sl: prog_sl},
			success: (res)=>{
				var obj = JSON.parse(res);
				$.each(obj, (i, val)=>{
					crList+='<option value="'+val['id'].trim()+'">'+val['title'].trim()+' ('+val['c_code'].trim()+')</option>';
				});
				$('#'+cp_id+'course').html(crList);
				$('#'+cp_id+'course').selectpicker('refresh');
			},
			error: (errors)=>{
				console.log(errors);
			}
		})
	}
	
	function deleteTest(title, id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this Test: "+title,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, delete it!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value) {
				$('#loading').show();
				$.ajax({
					url: baseURL+'Exam/removeTest/?tid='+id,
					type: 'GET',
					success: (res)=>{
						$('#loading').hide();
						if(res)
						{
							swal({
								title: 'Deleted!',
								text: 'The Test has been deleted.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							}).then((result)=>{
								$('#ttc_'+id).remove();
							})
						}else{
							swal({
								title: 'Failed!',
								text: 'Something went worng.',
								type: 'warning',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							})
						}
					}
				})
			}
		})
	}
	function unPublishTest(title, id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want unpublish of this Test: "+title,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, unpublish it!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value) {
				$('#loading').show();
				$.ajax({
					url: baseURL+'Exam/unPublishTest',
					type: 'GET',
					data: { tid: id },
					success: (res)=>{
						$('#loading').hide();
						if(res)
						{
							swal({
								title: 'Successful!',
								text: 'The Test has been unpublished.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							}).then((result)=>{
								getAllTestList('', '');
							})
						}else{
							swal({
								title: 'Failed!',
								text: 'Something went worng.',
								type: 'warning',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							})
						}
					}
				})
			}
		})
	}
	function publishTestNow(title, id, dur)
	{
		$('#tp_name').html(title);
		$('#tp_dur').html(dur+' minutes');
		$('#tid').val(id);
		$('#tpModal').modal('show');
	}
	
	$('#launch').on('change', ()=>{
		var ltype = parseInt($('#launch').val());
		if(ltype==2){
			$('#sdatetm').show();
			$('#edatetm').hide();
		}else if(ltype==3){
			$('#sdatetm').show();
			$('#edatetm').show();
		}else{
			$('#sdatetm').hide();
			$('#edatetm').hide();
		}
	})
</script>