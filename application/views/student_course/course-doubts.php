<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title"><?php echo $title; ?></h4>
			  </div>
			  <button data-toggle="modal" data-target="#doubtModal" class="btn btn-link btn-primary pull-right"><i class="material-icons">add</i> Submit your doubts</button>
			</div>
			<div class="card-body">
				<div class="material-datatables">
					<table class="table table-striped table-no-bordered table-hover" id="doubtList" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%">Sl.</th>
								<th width="45%">Doubts</th>
								<th width="40%">Answer</th>
								<th width="10%">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach($stud_dbs as $drow){ ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td>
										<?php 
											echo trim($drow->doubts); 
											if($drow->fac_sl==0){
												echo '<br>Any Teacher';
											}else{
												echo '<br>To: '.$drow->toname; 
											}
										?>
									</td>
									<td>
										<?php 
											if(trim($drow->db_ans)!=""){
												echo 'By: '.$drow->fromname.'<br>'.trim($drow->db_ans); ; 
											}
										?>
									</td>
									<td>
										<?php
											$stat = trim($drow->status);
											echo ($stat=='pending')? '<span class="label label-warning">Pending</span>' : '<span class="label label-success">Done</span>';
										?>
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
<div class="modal fade" id="doubtModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title">Submit your doubts</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmDoubts">
			<div class="modal-body">
				<div class="form-group mb-0">
					<select name="dbt_prof" id="dbt_prof" class="selectpicker" data-style="select-with-transition" title="Select a Teacher">
						<option value="0">Any teacher</option>
						<?php 
							foreach($pprof as $trow){ 
								echo '<option value="'.$trow->id.'">'.trim($trow->name).'</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group mb-0">
					<select name="dbt_schc" id="dbt_schc" class="selectpicker" data-style="select-with-transition" title="Select a schedule class">
						<?php 
							foreach($schClass as $scrow){ 
								echo '<option value="'.$scrow->sl.'">'.$scrow->class_title.' ('.trim($scrow->class_type).')</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group">
					<input type="hidden" value="<?php echo $prog_id; ?>" name="sch_prog" id="dbt_prog">
					<input type="hidden" value="<?php echo $cd[0]->id; ?>" name="sch_course" id="dbt_course">
					<label for="dbt_details" class="text-dark">Doubt</label><br>
					<textarea class="form-control" name="dbt_details" id="dbt_details"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<input type="reset" style="visibility:hidden">
			  <button type="submit" class="btn btn-link">Save</button>
			  
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('#doubtList').DataTable();
	$('#dbt_prof').selectpicker('refresh');
	$('#dbt_schc').selectpicker('refresh');
	$('#dbt_details').summernote();
	$('#frmDoubts').on('submit', (e)=>{
		//$('#loading').show();
		e.preventDefault();
		var frmDoubtData = new FormData($('#frmDoubts')[0]);
		$.ajax({
			beforeSend: function( xhr ) {
				//xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
				$('#loading').show();
			},
			url: baseURL+'Student/addDoubts',
			type: 'POST',
			data: frmDoubtData,
			cache : false,
			processData: false,
			contentType: false,
			enctype: 'multipart/form-data',
			async: false,
			success: (res)=>{ 
				$('#doubtModal').modal('hide');
				$('#frmDoubts')[0].reset();
				$('#loading').hide();
				var obj = JSON.parse(res);
				swal(
				  'Doubt',
				  obj.msg,
				  obj.status
				).then(result=>{
					getBtnContent(<?php echo trim("'doubts', ".$cd[0]->id.", ".$prog_id); ?>);
				});
			},
			error: (errors)=>{
				console.log(errors);
			}
		});
	});
</script>