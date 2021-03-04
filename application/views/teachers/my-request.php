<div class="content">
	<div class="container">
		<div class="row">
			
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">My Program Approval Status</h4>
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
										  <th width="55%">Program</th>
										  <th width="30%">Purpose</th>
										  <th width="15%">Status</th>
										</tr>
									  </thead>
									  <tbody>
										<?php $i=1; foreach($myreq as $mrow){ ?>
										<tr>
										  <td><?php echo $i; ?></td>
										  <td>
											<?php
												echo $mrow->title."<br>".$mrow->code.", ".$mrow->category.", ";
												$type=intval(trim($mrow->type));
												if($type==1){
													echo 'Program Management';
												}else if($type==2){
													echo 'Learning Platform';
												}
											?>
										  </td>
										  <td><?php echo 'Requested for: '.$mrow->role; ?></td>
										  <td>
											<?php 
												$stat = trim($mrow->status);
												if($stat=='approved' || $stat=='accepted'){
													echo '<span class="label label-success">'.ucfirst($stat).'</span>';
												}else{
													echo '<span class="label label-warning">'.ucfirst($stat).'</span>';
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
	//$('#datatables').DataTable();
	
	var tabreq = $('#datatables').DataTable();
</script>