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
			
			<div class="col-md-12">
				<div class="card ">
					<div class="card-header card-header-primary card-header-text">
					  <div class="card-text">
						<h4 class="card-title">Teachers under: "
									<?php 
										echo $prog[0]->title; 
										$yearnm = trim($prog[0]->yearnm);
										if($yearnm!=''){
											echo ' (Academic year: '.$yearnm.')';
										}
								?>"</h4>
					  </div>
					  <a href="<?php echo base_url().'Admin/programList'; ?>" class="btn btn-sm btn-primary pull-right"><i class="material-icons">list</i> Program List</a>
					</div>
					<div class="card-body">
						<form id="frmProfs" method="POST" class="d-flex w-100">
							<div class="form-group mr-3">
							<label>Multi-select professors to link *</label>
								<select class="selectpicker" name="nlprofs[]" multiple="multiple" id="nlprofs" data-title="Link Professors" data-style="select-with-transition">
									<?php
										foreach($nlprofs as $nlow)
										{
											echo '<option value="'.$nlow->id.'">'.$nlow->name.'</option>';
										}
									?>	
								</select>
								<input type="hidden" name="prog_id" id="prog_id" value="<?php echo $prog_id; ?>"/>
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-success btn-sm" id="btn_oper">Link it!</button>
							</div>
						</form>
					</div>
				</div>
				
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Teachers List</h4>
					</div>
					<div class="card-body">
						<div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width: 100%; display: none;">Loading the list. Please wait...</div>
						<div class="material-datatables">
							<table class="table table-striped table-no-bordered table-hover dtinsstrm" id="profTable" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="5%">Sl.</th>
										<th width="50%">Name</th>
										<th width="20%">Email</th>
										<th width="15%">Phone</th>
										<th width="10%">Action</th>
									</tr>
								</thead>
								<tbody id="profList">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url();?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/org-admin.js"></script>
<script>
	$(window).on('load', ()=>{
		getLinkedTeachers(<?php echo $prog_id; ?>);
		$('#progTable').DataTable();
	});
</script>