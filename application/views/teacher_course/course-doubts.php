<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title"><?php echo $title; ?></h4>
			  </div>
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
											echo 'By: '.$drow->studname.'<br>';
											echo '<strong>Q'.$i.')</strong> '.trim($drow->doubts); 
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
											}else{
												echo '<button type="button" onClick="giveDoubtAns('.$drow->sl.')" class="btn btn-sm btn-info">Answer</button>';
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
			  <h4 class="modal-title">Submit answer to this doubts</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmDoubts">
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" value="0" name="dbt_id" id="dbt_id">
					<label for="dbt_details" class="text-dark">Answer</label><br>
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
	$('#dbt_details').summernote();
	function giveDoubtAns(dbtid)
	{
		$('#dbt_id').val(dbtid);
		$('#doubtModal').modal('show');
	}
	$('#frmDoubts').on('submit', (e)=>{
		$('#loading').show();
		e.preventDefault();
		var frmDoubtData = new FormData($('#frmDoubts')[0]);
		$.ajax({
			beforeSend: function( xhr ) {
				//xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
				$('#loading').show();
			},
			url: baseURL+'Teacher/updateDoubts',
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