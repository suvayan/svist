<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
						<h3 class="card-title">
                            Program's List
                            <a class="btn btn-primary btn-sm pull-right" href="<?php echo base_url();?>Subadmin/addEditProgram"><i class="material-icons">add</i> Add New Program</a>
                        </h3>
					</div>
                    <div class="card-body">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width: 100%; display: none;">Loading the list. Please wait...</div>
                        <div class="material-datatables">
                            <table class="table table-hover table-striped" width="100%" id="progTable">
								<thead>
									<tr>
                                        <th width="5%">Sl#</th>
										<th width="25%">Program</th>
										<th width="15%">Department</th>
										<th width="10%">Duration</th>
										<th width="15%">Date-Time</th>
										<th width="15%">Actions</th>
									</tr>
								</thead>
								<tbody id="porgList">
								
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
<script src="<?php echo base_url();?>/assets/js/sub-admin.js"></script>
<script>
	$(window).on('load', ()=>{
        $('#progTable').DataTable();
		programList();
	});
</script>