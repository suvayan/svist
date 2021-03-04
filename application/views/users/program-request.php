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
						<h4 class="card-title">My Program Status</h4>
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
										  <th width="35%">Payment Status</th>
										  <th width="15%">Status</th>
										</tr>
									  </thead>
									  <tbody>
										<?php 
											$i=1; 
											foreach($rusers as $row){ 
											$af = trim($row->approve_flag);
											$ft = trim($row->feetype);
										?>
										<tr>
										  <td><?php echo $i." ".$row->prog_id; ?></td>
										  <td><?php echo trim($row->title).'<br>'.trim($row->email).'<br>'.trim($row->mobile); ?></td>
										  <td>
											<?php
												if($ft=='Free'){
													echo 'Free Program';
												}else if($ft=='Paid'){
													if($af=='1'){
														$dur = intval(trim($row->duration))*12;
														$st = intval(trim($row->sem_type));
														$tfee = intval(trim(${'spcdata_'.$i}[0]->totalfees));
														$dis = intval(trim(${'spcdata_'.$i}[0]->discount));
														$pdone = intval(trim(${'spcdata_'.$i}[0]->payment_done));
														if($dis!=0){
															$payable = $tfee*(1-($dis/100));
															echo '<strike>Rs. '.$tfee.'</strike><br><strong>Pay: Rs. '.$payable.'</strong>';
														}else{
															$payable = $tfee;
															echo '<strong>Pay: Rs. '.$payable.'</strong>';
														}
														if(($payable-$pdone)==0){
															echo '<br>Payment Completed';
														}else{
															if($st==0){
																$pnow = $payable;
															}else{
																$pnow = $payable/($dur/$st);
															}
															echo '<br><button type="button" onClick="getPaymentInfo('.${'spcdata_'.$i}[0]->sl.', '.$pnow.', '.${'spcdata_'.$i}[0]->prog_id.')" class="btn btn-info">Pay Now: '.$pnow.'</button>';
														}
													}else{
														echo 'Approval needed';
													}
												}
											?>
										  </td>
										  <td>
											<?php 
												if($af=='2'){
													echo '<span class="label label-success">Final Approved</span>';
												}else if($af=='1'){
													echo '<span class="label label-danger">First Approved</span>';
												}else{
													echo '<span class="label label-warning">Pending</span>';
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
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <h4 class="modal-title text-center font-weight-bold">Payment Confirmation</h4>
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<i class="material-icons">clear</i>
			  </button>
			</div>
			<form id="frmPayment" action="<?php echo base_url('Student/payNow'); ?>" method="POST">
			<div class="modal-body">
				<div class="form-group">
					<label>Fullname</label><br>
					<input type="text" name="fullname" id="fullname" value="<?php echo $_SESSION['userData']['name']; ?>" class="form-control" readonly/>
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['userData']['userId']; ?>"/>
					<input type="hidden" name="spc_id" id="spc_id" value=""/>
					<input type="hidden" name="prog_id" id="prog_id" value=""/>
				</div>
				<div class="form-group">
					<label>Email</label><br>
					<input type="text" name="email" id="email" value="<?php echo $_SESSION['userData']['email']; ?>" class="form-control" readonly/>
				</div>
				<div class="form-group">
					<label>Mobile</label><br>
					<input type="text" name="mobile" id="mobile" value="<?php echo $_SESSION['userData']['mobile']; ?>" class="form-control" readonly/>
				</div>
				<div class="form-group">
					<label>Amount Payable</label><br>
					<input type="number" name="pamount" id="pamount" value="" class="form-control" readonly/>
				</div>
			</div>
			<div class="modal-footer">
			  <button type="submit" class="btn btn-link" id="btn_save_pass">Pay Now</button>
			  <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Close</button>
			</div>
			</form>
		  </div>
		</div>
	  </div>
<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script>
	function getPaymentInfo(spcid, pamt, prog_id)
	{
		$('#spc_id').val(spcid);
		$('#prog_id').val(prog_id);
		$('#pamount').val(pamt);
		$('#payModal').modal('show');
	}
</script>