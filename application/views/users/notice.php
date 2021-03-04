<div class="content">
	<div class="container-fluid">
		<div class="row">
		
			<div class="col-lg-10 col-md-10 mx-auto">
				<div class="card">
					<div class="card-header card-header-info">
						<h3 class="card-title">All Notices</h3>
					</div>
					
					<div class="card-body">
						<div class="material-datatables">
							<table class="table table-striped table-no-bordered table-hover" id="tblNotice" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="5%">Sl.</th>
										<th width="40%">Notice</th>
										<th width="45%">Details</th>
										<th width="10%">By</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; foreach($notices as $nrow){ ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td>
												<?php 
													echo trim($nrow->title).'<br>For Program:'.$nrow->ptitle.'<br>Course: '.$nrow->pc_title; 
													if($nrow->file_name!=null){
														if(file_exists('./uploads/programs/'.$nrow->file_name)){
															echo '<br><a href="'.base_url().'uploads/programs/'.$nrow->file_name.'" target="_blank">Download file</a>';
														}
													}
												?>
											</td>
											<td><?php echo $nrow->details; ?></td>
											<td><?php echo trim($nrow->first_name." ".$nrow->last_name); ?></td>
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

<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('#tblNotice').DataTable();
	});
</script>