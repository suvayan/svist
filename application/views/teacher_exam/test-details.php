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
	<div class="container-fluid" id="page_body">
		
		<div class="card" style="background:transparent;">
			<div class="card-header card-header-tabs card-header-primary">
			  <div class="nav-tabs-navigation">
				<div class="nav-tabs-wrapper">
				  <span class="nav-tabs-title"><?php echo $test[0]->title.' (Marks: '.trim($test[0]->marks).', Duration: '.trim($test[0]->duration).')'; ?></span>
				  <ul class="nav nav-tabs pull-right" data-tabs="tabs">
					<li class="nav-item">
					  <a class="nav-link active" href="#main" data-toggle="tab">
						Test Details
						<div class="ripple-container"></div>
					  </a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="#candidate" data-toggle="tab">
						Candidates and Reports
						<div class="ripple-container"></div>
					  </a>
					</li>
				  </ul>
				</div>
			  </div>
			</div>
			<div class="card-body">
			  <div class="tab-content">
				<div class="tab-pane active" id="main">
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-sm btn-primary" onClick="sectionModal('add', '', '');">Create Section</button>
							<input type="hidden" name="ttid" id="ttid" value="<?php echo $test[0]->id; ?>"/>
							<input type="hidden" name="catid" id="catid" value="<?php echo $test[0]->cat_id; ?>"/>
							<input type="hidden" name="scatid" id="scatid" value="<?php echo $test[0]->scat_id; ?>"/>
							<?php
								if($test[0]->publish=='f'){
									echo '<button class="btn btn-sm btn-primary pull-right" onClick="publishTestNow(`'.trim($test[0]->title).'`, '.$test[0]->id.', '.trim($test[0]->duration).')">Create Section</button>';
								}
							?>
							
							<div class="progress-bar progress-bar-striped progress-bar-animated" id="sec_progress" style="width:100%; display:none;">Loading...</div>
							<div class="row" id="sec_pack">
							
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="candidate">
					<div class="progress-bar progress-bar-striped progress-bar-animated" id="candi_progress" style="width:100%; display:none;">Loading...</div>
					<div class="row" id="candi_pack"></div>
				</div>
			  </div>
			</div>
		</div>
		
	</div>
</div>
<div class="modal fade" id="secModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title" id="mheader"></h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form action="#" method="POST" id="frmSection">
		<div class="modal-body">
			<div class="form-group">
				<label for="sectitle">Title*</label>
				<input type="text" name="sectitle" id="sectitle" class="form-control" required="true"/>
				<input type="hidden" name="scid" id="scid" value="0"/>
				<input type="hidden" name="tid" id="tid" value="<?php echo $test[0]->id; ?>"/>
			</div>
		</div>
		<div class="modal-footer">
			<input type="reset" style="visibility:hidden;"/>
			<button type="submit" class="btn btn-link" id="btn_id">Save</button>
			<button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
		</div>
		</form>
	  </div>
	</div>
</div>
<div class="modal fade" id="quesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-header">
		  <h4 class="modal-title">Check/Uncheck questions</h4>
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="material-icons">clear</i>
		  </button>
		</div>
		<form id="frmQBSection" method="POST">
		<div class="modal-body" style="max-height: 400px; overflow-y:scroll;">
			<div class="row" id="qbs_pack"></div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-success btn-link">Add Selected Questions</button>
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
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>
<script>
	$(document).ready(function() {
		getAllSections();
		getAllTestCandidates();
		$("form#frmSection").validate({
			errorPlacement: function(error, element) {
			  $(element).closest('.form-group').append(error);
			},
			submitHandler: function(form, e) {
				$('#loading').css('display', 'block');
				e.preventDefault();
				var frmData = new FormData($('#frmSection')[0]);
				$.ajax({
					url: baseURL+'Exam/cuSection',
					type: 'POST',
					data: frmData,
					cache : false,
					processData: false,
					contentType: false,
					async: false,
					success: (resp)=>{ 
						$('#secModal').modal('hide');
						$('#frmSection')[0].reset();
						$('#loading').css('display', 'none');
						var obj = JSON.parse(resp);
						if(obj['status']=='success'){
							getAllSections();
						}
						$.notify({icon:"add_alert",message:'The Section '+obj['msg']},{type:obj['status'],timer:3e3,placement:{from:'top',align:'right'}})
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
					},
					error: (errors)=>{
						console.log(errors);
					}
				});
			}
		});
	});
	function publishTestNow(title, id, dur)
	{
		$('#tp_name').html(title);
		$('#tp_dur').html(dur+' minutes');
		$('#tid').val(id);
		$('#tpModal').modal('show');
	}
	function sectionModal(func, stitle, sec_id)
	{
		if(func=='add'){
			$('#mheader').html("Create Section");
			$('#sectitle').val(stitle);
			$('#secModal').modal('show');
		}else{
			$('#mheader').html("Update Section");
			$('#sectitle').val(stitle);
			$('#scid').val(sec_id);
			$('#secModal').modal('show');
		}
	}
	function deleteSection(title, sec_id)
	{
		swal({
			title: 'Are you sure?',
			text: "You want to delete this Section: "+title,
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
					url: baseURL+'Exam/removeSection/?sid='+sec_id,
					type: 'GET',
					success: (res)=>{
						$('#loading').hide();
						if(res)
						{
							swal({
								title: 'Deleted!',
								text: 'The Section has been deleted.',
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
	
	function getAllSections()
	{
		var ttid = $('#ttid').val();
		$('#sec_pack').html("");
		$('#sec_progress').show();
		$.ajax({
			url: baseURL+'Exam/getAllSections',
			type: 'GET',
			data: { id: ttid },
			success: (resp)=>{
				$('#sec_progress').hide();
				$('#sec_pack').html(resp);
			}
		});
	}
	function getAllTestCandidates()
	{
		$('#candi_pack').html("");
		var ttid = $('#ttid').val();
		$('#candi_progress').show();
		$.ajax({
			url: baseURL+'Exam/getAllTestCandidates',
			type: 'GET',
			data: { id: ttid },
			success: (resp)=>{
				$('#candi_progress').hide();
				$('#candi_pack').html(resp);
			}
		});
	}
	
	function questionModal(sec_id)
	{
		$('#loading').css('display', 'block');
		$('#quesModal').modal('hide');
		var catid = $('#catid').val();
		var scatid = $('#scatid').val();
		$('#qbs_pack').html("");
		$('#qbs_progress').show();
		$.ajax({
			url: baseURL+'Exam/getAllQBsQuestions',
			type: 'GET',
			data: { catid: catid, scatid: scatid, sid: sec_id },
			success: (resp)=>{
				$('#qbs_progress').hide();
				$('#qbs_pack').html(resp);
				$('#ques_tbl').DataTable();
				$('#loading').css('display', 'none');
				$('#quesModal').modal('show');
			}
		});
	}

	function questionSelectForTest(sec_id){
		var catid = $('#catid').val();
		var scatid = $('#scatid').val();
		$('#qbs_pack').html("");
		$('#qbs_progress').show();
		$.ajax({
			url: baseURL+'Teacher/getAllQBsQuestions',
			type: 'GET',
			data: { catid: catid, scatid: scatid, sid: sec_id },
			success: (resp)=>{
				//console.log(resp);
				$('#page_body').html(resp);
			}
		});
	}

	$("form#frmQBSection").submit(function(e){
		e.preventDefault();
		$('#loading').show();
		var frmSecQuesData = new FormData($('#frmQBSection')[0]);
		$.ajax({
			url:baseURL+'Exam/cuSecQuestion',
			type: 'POST',
			data: frmSecQuesData,
			cache : false,
			processData: false,
			contentType: false,
			async: false,
			success: (resp)=>{ 
				$('#loading').css('display', 'none');
				var obj = JSON.parse(resp);
				if(obj['status']=='success'){
					$('#qbs_pack').html("");
					$('#quesModal').modal('hide');
					getAllSections();
				}
				$.notify({icon:"add_alert",message:obj.msg},{type:obj.status,timer:3e3,placement:{from:'top',align:'right'}})
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	});
	
	function removeSecQuestion(){
		$('#qbs_pack').html("");
	}
	
	function inviteSingle(uid){
		var tp_id = $('#tp_id').val();
		var ttid = $('#ttid').val();
		$('#loading').css('display', 'block');
		$.ajax({
			url: baseURL+'Exam/singleTestInvite',
			type: 'POST',
			data: { uid: uid, tp_id: tp_id, ttid: ttid },
			success: (resp)=>{
				$('#loading').css('display', 'none');
				if(resp){
					swal({
						title: 'Successful!',
						text: 'The candidate has been selected and notified via mail.',
						type: 'success',
						confirmButtonClass: "btn btn-success",
						buttonsStyling: false
					}).then((result)=>{
						getAllTestCandidates();
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
		});
	}
	function inviteSelected()
	{
		var cb = [];
		var i=1;
		$(".chkcandi").each(function() {
		  if($('#ch_candi_'+i).is(':checked')){
			  cb.push($('#ch_candi_'+i).val());
		  }
		  i++;
	   });
		var tp_id = $('#tp_id').val();
		var ttid = $('#ttid').val();
		$('#loading').css('display', 'block');
		$.ajax({
			url: baseURL+'Exam/multipleTestInvite',
			type: 'POST',
			data: { uid: cb, tp_id: tp_id, ttid: ttid },
			success: (resp)=>{
				//console.log(resp)
				$('#loading').css('display', 'none');
				if(resp){
					swal({
						title: 'Successful!',
						text: 'The candidates have been selected and notified via mail.',
						type: 'success',
						confirmButtonClass: "btn btn-success",
						buttonsStyling: false
					}).then((result)=>{
						getAllTestCandidates();
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
		});
	}
	function sltAllQues(id)
	{
		var checked = $('#chk_qb'+id).is(':checked');
		if ($('.cbquestion_'+id).length > 0){
			if(checked){
			   $('.cbquestion_'+id).each(function() {
				  $('.cbquestion_'+id).prop('checked',true);
			   });
			 }else{
			   $('.cbquestion_'+id).each(function() {
				 $('.cbquestion_'+id).prop('checked',false);
			   });
			 } 
		}
	}
	function randomToggle(sid, bool)
	{
		var tqnum = parseInt($('#secNumQues_'+sid).attr('maxlength'));
		if(tqnum>0){
			if($('#randfg_'+sid).is(':checked')){
				$('#secNumQues_'+sid).removeAttr('disabled');
				$('#secBenchmark_'+sid).removeAttr('disabled');
			}else{
				$('#secNumQues_'+sid).attr('disabled');
				$('#secBenchmark_'+sid).attr('disabled');
				$('#secNumQues_'+sid).val(0);
				$('#secBenchmark_'+sid).val(0);
			}
			if(bool){
				upRandomSec(sid);
			}
		}else{
			$.notify({icon:"add_alert",message:'Must add questions to this section.'},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
		}
	}
	function upRandomSec(sid)
	{
		var tqnum = parseInt($('#secNumQues_'+sid).attr('maxlength'));
		var rd = ($('#randfg_'+sid).is(':checked'))? true : false;
		var qnum = $('#secNumQues_'+sid).val();
		var bnch = $('#secBenchmark_'+sid).val();
		if(rd && (qnum>tqnum)){
			$.notify({icon:"add_alert",message:'`Question number` must not exceed total added question in section.'},{type:'warning',timer:3e3,placement:{from:'top',align:'right'}})
			return true;
		}
		$.ajax({
			url: baseURL+'Exam/updateSecRandom',
			type: 'POST',
			data: { sid: sid, rdfg: rd, qnum: qnum, bnch: bnch },
			success: (resp)=>{
				if(resp){
					$.notify({icon:"add_alert",message:'The section has been updated.'},{type:'success',timer:3e3,placement:{from:'top',align:'right'}})
				}else{
					$.notify({icon:"add_alert",message:'Failed!, something went worng.'},{type:'danger',timer:3e3,placement:{from:'top',align:'right'}})
				}
			}
		});
	}

	function testReportView(){
		$.ajax({
			url  : baseURL+'Teacher/testReport',
			type : 'POST',
			success :  (resp)=>{
				console.log(resp);
				//$('#candi_progress').hide();
				$('#page_container').html(resp);
			}
		});
	}
</script>