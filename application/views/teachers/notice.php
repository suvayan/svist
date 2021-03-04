<style>
.dropdown bootstrap-select {
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
		
			<div class="col-lg-10 col-md-10 mx-auto">
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">All Notices
						<button type="button" onClick="noticeModal('add', '')" class="btn btn-sm btn-primary pull-right"><i class="material-icons">add</i> Add Notice</button>
						</h3>
						
					</div>
					
					<div class="card-body">
						<div class="material-datatables">
							<table class="table table-striped table-no-bordered table-hover" id="tblNotice" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="5%">Sl.</th>
										<th width="35%">Notice</th>
										<th width="40%">Details</th>
										<th width="10%">By</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; foreach($notices as $nrow){ ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td>
												<?php 
													echo '<strong>'.trim($nrow->title).'</strong><br>For Program:'.$nrow->ptitle.'<br>Course: '.((trim($nrow->course_sl)==0)? 'All courses' : trim($nrow->pc_title)); 
													if($nrow->file_name!=null){
														if(file_exists('./uploads/programs/'.$nrow->file_name)){
															echo '<br><a href="'.base_url().'uploads/programs/'.$nrow->file_name.'" target="_blank">Download file</a>';
														}
													}
												?>
											</td>
											<td><?php echo $nrow->details; ?></td>
											<td><?php echo trim($nrow->first_name." ".$nrow->last_name); ?></td>
											<td>
												<button type="button" onClick="noticeModal('edit', <?php echo $nrow->sl; ?>)" class="btn btn-sm btn-success"><i class="material-icons">edit</i> Edit</button><br>
												<button type="button" onClick="deleteNotice(<?php echo '`'.$nrow->title.'`, '.$nrow->sl; ?>);" class="btn btn-sm btn-danger"><i class="material-icons">delete</i> Delete</button>
											</td>
										</tr>
									<?php $i++; } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<div class="modal fade" id="noticeM" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title" id="not_head">Add Notice</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmNotice" enctype="multipart/form-data">
				<div class="modal-body">
				  <div class="form-group mb-0">
					<label for="nottitle" class="text-dark">Title</label>
					<input type="text" class="form-control" name="nottitle" id="nottitle" required="true">
					<input type="hidden" name="pn_id" id="pn_id" value="0">
				  </div>	
				  <div class="form-group mb-0">
					<select name="not_prog" id="not_prog" onChange="getProgCourses(this.value, 'not_', '');" class="selectpicker" data-style="select-with-transition" title="Select an program*" required="true">
						<?php 
							foreach($programs as $row){ 
								echo '<option value="'.$row->id.'">'.$row->title.' ('.$row->code.')</option>';
							}
						?>
					</select>
				  </div>
				  <div class="form-group mb-0">
					<select name="not_course" id="not_course" class="selectpicker" data-style="select-with-transition" title="Select an course*" required="true">

					</select>
				  </div>
				  <div class="custom-file">
					<input type="file" class="custom-file-input" name="fl_notice" id="fl_notice" accept="application/pdf, .doc, .docx">
					<label class="custom-file-label text-dark" for="fl_notice">Upload your file</label>
				  </div>
				  <div class="form-group mt-5">
					<label for="notdetails" class="text-dark">Details</label><br>
					<textarea class="form-control w-100" name="notdetails" id="notdetails"></textarea>
				  </div>
				</div>
				<div class="modal-footer">
				  <input type="reset" style="visibility:hidden">
				  <button type="submit" id="not_btn" class="btn btn-link">Save</button>
				  
				  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#tblNotice').DataTable();
		$('#details').summernote();
		$("form#frmNotice").validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmNoticeData = new FormData($('#frmNotice')[0]);
				$.ajax({
					url: baseURL+'Teacher/addNotice',
					type: 'POST',
					data: frmNoticeData,
					cache : false,
					processData: false,
					contentType: false,
					enctype: 'multipart/form-data',
					async: false,
					success: (res)=>{ 
						$('#noticeM').modal('hide');
						$('#frmNotice')[0].reset();
						$('#loading').css('display', 'none');
						var obj = JSON.parse(res);
						//console.log(obj)
						swal(
						  'Notice',
						  obj.msg,
						  obj.status
						).then(result=>{
							window.location.reload();
						});
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
	})
	
	$("#note_file").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
	
	function noticeModal(func, nid)
	{
		if(func=='add'){
			$('#frmNotice')[0].reset();
			$('#not_btn').html('Save');
			$('#pn_id').val(0);
			$('#not_head').html('Add Notice');
			$('#noticeM').modal('show');
		}else{
			$('#not_btn').html('Update');
			$('#not_head').html('Update Notice');
			$('#pn_id').val(nid);
			$('#loading').css('display', 'block');
			$.ajax({
				url: baseURL+'Teacher/getNoticeByID',
				type: 'GET',
				data: {
					id: nid
				},
				success: (data)=>{
					$('#loading').fadeOut(1000);
					var res = JSON.parse(data);
					$('#nottitle').val(res[0].title);
					$('#not_prog').val(res[0].program_sl).selectpicker('refresh');
					getProgCourses(res[0].program_sl, 'not_', res[0].course_sl);
					$('#not_course').val(res[0].course_sl).selectpicker('refresh');
					$('#notdetails').val(res[0].details);
					$('#noticeM').modal('show');
				}
			});
			//$('#noticeM').modal('show');
		}
	}
	
	function getProgCourses(prog_sl, cp_id, cid)
	{
		var crList='<option value="0">All Courses</option>';
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
	
	function deleteNotice(title, id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this notice: "+title,
			type: 'warning',
			showCancelButton: true,
			confirmButtonClass: 'btn btn-success',
			cancelButtonClass: 'btn btn-danger',
			confirmButtonText: 'Yes, delete it!',
			buttonsStyling: false
		}).then(function() {
			$('#loading').show();
			$.ajax({
				url: baseURL+'Teacher/delNotice/?pnid='+id,
				type: 'GET',
				success: (res)=>{
					$('#loading').fadeOut(1000);
					if(res)
					{
						swal({
							title: 'Deleted!',
							text: 'The Notice has been deleted.',
							type: 'success',
							confirmButtonClass: "btn btn-success",
							buttonsStyling: false
						}).then((result)=>{
							window.location.reload();
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
		}).catch(swal.noop)
	}
</script>