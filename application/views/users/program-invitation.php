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
	<div class="container">
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">All Invitations for Programs</h4>
					  </div>
					</div>
					<div class="card-body">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12">
								<div class="material-datatables">
									<table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
									  <thead>
										<tr>
										  <th width="5%">Sl</th>
										  <th width="45%">Program Title</th>
										  <th width="35%">Invited By</th>
										  <th width="15%">Status</th>
										</tr>
									  </thead>
									  <tbody>
										<?php 
											$i=1; 
											foreach($invData as $row){ 
											$stat = trim($row->status);
											$id = $row->sl;
										?>
										<tr>
										  <td><?php echo $i; ?></td>
										  <td><?php echo $row->prog_name.'<br><strong>For Role: </strong>'.$row->role; ?></td>
										  <td><?php echo $row->hostname; ?></td>
										  <td class="td-actions">
											<?php 
												if($stat=='accepted'){
													echo '<span class="label label-success">Accepted</span>';
												}else if($stat=='rejected'){
													echo '<span class="label label-danger">Rejected</span>';
												}else{
													echo '<span class="label label-warning" id="label_'.$id.'">Pending</span>';
													echo '<div id="btn_action_'.$id.'">
															<button type="button" onClick="updateInvite('.$id.','.$row->program_id.',`'.trim($row->role).'`, `accept`);" rel="tooltip" title="Accept" class="btn btn-success btn-round">
															  <i class="material-icons">done</i>
															</button>
															<button type="button" onClick="updateInvite('.$id.','.$row->program_id.',`'.trim($row->role).'`, `reject`);" rel="tooltip" title="Denied" class="btn btn-danger btn-round">
															  <i class="material-icons">close</i>
															</button>
														  </div>';
												}
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
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	function updateInvite(puid, pid, role, stat)
	{
		Swal.fire({
		  title: 'Respond',
		  text: "Do you want to "+stat+" this program?",
		  type: 'question',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, please!'
		}).then((result) => {
		  if (result.value) {
			//sendProgInviteData('Teacher', prog);
			$('#loading').show();
			$.ajax({
				url: baseURL+uType+'/applyInviteToPRogram',
				type: 'POST',
				data: {puid:puid, pid:pid, role:role, stat:stat},
				success: (res)=>{
					var obj = JSON.parse(res);
					if(obj['status'])
					{
						$('#loading').hide();
						Swal.fire(
						  'Responded!',
						  obj['msg'],
						  'success'
						).then(result=>{
							$('#btn_action_'+puid).fadeOut(2000);
							$('#label_'+puid).removeClass('label-warning');
							if(stat=='accept'){
								$('#label_'+puid).addClass('label-success');
								$('#label_'+puid).html('Accepted');
							}else{
								$('#label_'+puid).addClass('label-danger');
								$('#label_'+puid).html('Rejected');
							}
						})
					}else{
						Swal.fire(
						  'Failed!',
						  'Something wen wrong. Server error.',
						  'error'
						)
					}
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		  }
		});
	}
</script>