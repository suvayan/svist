<style>
.dropdown.bootstrap-select {
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
			<aside class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div id="accordion" role="tablist">
							<div class="card-collapse">
							  <div class="card-header card-header-info" role="tab" id="headingOne">
								<h5 class="mb-0">
								  <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed text-white">
									Question Banks
									<i class="material-icons pull-right">keyboard_arrow_down</i>
								  </a>
								</h5>
							  </div>
							  <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
								<div class="card-body">
									<button type="button" onClick="quesBankModal('add', '')" class="btn btn-sm btn-primary">Add Question Bank</button>
									<div id="qb_list">
										<div class="progress-bar progress-bar-striped progress-bar-animated" id="qb_progress" style="width:100%;">Loading...</div>
									</div>
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</aside>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title" id="main_qbqs">All Questions
						</h3>
					</div>
					<div class="card-body">
						<div class="progress-bar progress-bar-striped progress-bar-animated w-100" id="qbs_progress" style="display:none;">Loading...</div>
						<div id="qbs_list">
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="qbModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="qb_head"></h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmQB" enctype="multipart/form-data">
				<div class="modal-body">
				  <div class="form-group mb-0">
					<label for="qbtitle" class="text-dark">Title</label>
					<input type="text" class="form-control" name="qbtitle" id="qbtitle" required="true">
					<input type="hidden" name="qb_id" id="qb_id" value="0">
				  </div>				  
				  <div class="form-group mb-0">
					<select name="qb_prog" id="qb_prog" onChange="getProgCourses(this.value, 'qb_', '');" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<?php 
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
						?>
					</select>
				  </div>
				  <div class="form-group mb-0">
					<select name="qb_course" id="qb_course" class="selectpicker" data-style="select-with-transition" title="Select an course*" required="true">

					</select>
				  </div>
				</div>
				<div class="modal-footer">
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" id="qb_btn" class="btn btn-link">Save</button>
				  
				  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function(){
		getAllQuestionBanksList();
		$('#qbdetails').summernote();
		$('#qbstitle').summernote();
		$("form#frmQB").validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmQBData = new FormData($('#frmQB')[0]);
				$.ajax({
					url: baseURL+'Exam/cuQuestionBank',
					type: 'POST',
					data: frmQBData,
					cache : false,
					processData: false,
					contentType: false,
					async: false,
					success: (resp)=>{ 
						$('#qbModal').modal('hide');
						$('#frmQB')[0].reset();
						$('#loading').css('display', 'none');
						var obj = JSON.parse(resp);
						if(obj['status']=='success'){
							getAllQuestionBanksList();
						}
						$.notify({icon:"add_alert",message:'The Question Bank '+obj['msg']},{type:obj['status'],timer:3e3,placement:{from:'top',align:'right'}})
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
	});
	
	function getAllQuestionBanksList()
	{
		$('#qb_list').html();
		$('#qb_progress').show();
		$.ajax({
			url: baseURL+'Exam/getAllUserQBs',
			type: 'GET',
			async: false,
			success: (data)=>{
				$('#qb_progress').hide();
				$('#qb_list').html(data);
			}
		})
	}
	function getAllQuestionsList(title, qb_id)
	{
		$('#qbs_progress').show();
		$('#qbs_list').html();
		var qhtitle = (title=='')? 'All Questions' : 'All Questions under '+title+'<a href="'+baseURL+'Exam/cruQuestion/?qbid='+btoa(qb_id)+'" class="btn btn-sm btn-primary pull-right"><i class="material-icons">add</i> Add Question</a>';
		$('#main_qbqs').html(qhtitle);
		$.ajax({
			url: baseURL+'Exam/getAllUserQues',
			type: 'GET',
			data: {qbid: qb_id},
			async: false,
			success: (data)=>{
				$('#qbs_progress').hide();
				$('#qbs_list').html(data);
			}
		})
	}
	
	function quesBankModal(func, qid)
	{
		if(func=='add'){
			$('#frmQB')[0].reset();
			$('#qb_btn').html('Save');
			$('#qb_id').val(0);
			$('#qb_head').html('Add Question Bank');
			$('#qb_prog').selectpicker('refresh');
			$('#qb_course').selectpicker('refresh');
			$('#qbModal').modal('show');
		}else{
			$('#qb_btn').html('Update');
			$('#qb_head').html('Update Question Bank');
			$('#qb_id').val(qid);
			$('#loading').css('display', 'block');
			$.ajax({
				url: baseURL+'Exam/getQBsByID',
				type: 'GET',
				data: {
					id: qid
				},
				success: (data)=>{
					$('#loading').hide();
					var res = JSON.parse(data);
					$('#qbtitle').val(res[0].name);
					$('#qb_prog').val(res[0].cat_id).selectpicker('refresh');
					getProgCourses(res[0].cat_id, 'qb_', res[0].scat_id);
					$('#qb_course').val(res[0].scat_id).selectpicker('refresh');
					$('#qbModal').modal('show');
				}
			});
		}
	}
	
	function getProgCourses(prog_sl, cp_id, cid)
	{
		var crList='<option value="0" '+((cid===0)? 'selected': '')+'>All Courses</option>';
		$.ajax({
			url: baseURL+'Teacher/getCoursebyProg',
			type: 'GET',
			data: {sl: prog_sl},
			success: (res)=>{
				var obj = JSON.parse(res);
				$.each(obj, (i, val)=>{
					if(cid==val['id']){
						crList+='<option value="'+val['id'].trim()+'" selected>'+val['title'].trim()+' ('+val['c_code'].trim()+')</option>';
					}else{
						crList+='<option value="'+val['id'].trim()+'">'+val['title'].trim()+' ('+val['c_code'].trim()+')</option>';
					}
					
				});
				$('#'+cp_id+'course').html(crList);
				$('#'+cp_id+'course').selectpicker('refresh');
			},
			error: (errors)=>{
				console.log(errors);
			}
		})
	}
	
	function deleteQB(title, id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this question bank: "+title,
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
					url: baseURL+'Exam/removeQuesBank/?qid='+id,
					type: 'GET',
					success: (res)=>{
						$('#loading').hide();
						if(res)
						{
							swal({
								title: 'Deleted!',
								text: 'The question bank has been deleted.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							}).then((result)=>{
								$('#qbc_'+id).remove();
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
		}).catch(swal.noop)
	}
	function deleteQues(id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this question",
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, delete it!',
			buttonsStyling: false
		}).then(function(result) {
			if(result.value){
				$('#loading').show();
				$.ajax({
					url: baseURL+'Exam/removeQuestion/?qid='+id,
					type: 'GET',
					success: (res)=>{
						$('#loading').hide();
						if(res)
						{
							swal({
								title: 'Deleted!',
								text: 'The question has been deleted.',
								type: 'success',
								confirmButtonClass: "btn btn-success",
								buttonsStyling: false
							}).then((result)=>{
								$('#quesl_'+id).remove();
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
		}).catch(swal.noop)
	}
</script>