<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header card-header-info">
                        <h3 class="card-title">Teachers Of <?php echo $organization[0]->title;?> 
						<a href="<?php echo base_url('Subadmin/addEditTeacher'); ?>" class="btn btn-primary btn-sm pull-right" id="Add_prof"><i class="material-icons">add</i> Teachers</a>
						</h3>
                    </div>
                    <div class="card-body">
                        <?php
							if($this->session->flashdata('errors')!=''){
								echo '<div class="alert alert-warning alert-dismissible">
									  <button type="button" class="close" data-dismiss="alert">&times;</button>
									  '.$this->session->flashdata('errors').'
									</div>';
							}
						?>
						<div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div id="testList"></div>
                                <div class="material-datatables">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width: 100%; display: none;">Loading the list. Please wait...</div>
                                <div class="material-datatables p-5">
                                    <table class="table table-hover table-striped" width="100%" id="techTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">Sl#</th>
                                                <th width="10%">Image</th>
                                                <th width="20%">Name</th>
                                                <th width="30%">Contact</th>
                                                <th width="35%">Department</th>
                                                <th width="10%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="techList">
                                        
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
<script src="<?php echo base_url();?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>/assets/js/sub-admin.js"></script>
<script>
	$(window).on('load', ()=>{
        $('#techTable').DataTable();
		teacherList();
	});

	function changePassword(userid){
		Swal.fire({
		  title: 'Enter new password',
		  input: 'text',
		  inputAttributes: {
			autocapitalize: 'off'
		  },
		  showCancelButton: true,
		  confirmButtonText: 'Change Password',
		  showLoaderOnConfirm: true,
		  preConfirm: (name) => {
			return fetch(`<?php echo base_url(); ?>Subadmin/ChangeStudPassword/?id=${userid}&pass=${name}`)
			  .then(response => {
				if (!response.ok) {
				  throw new Error(response.statusText)
				}
				return response.json()
			  })
			  .catch(error => {
				Swal.showValidationMessage(
				  `Request failed: ${error}`
				)
			  })
		  },
		  allowOutsideClick: () => !Swal.isLoading()
		}).then((result) => {
		  if (result.value) {
			Swal.fire(
			  'Successfull',
			  'The password has been changed',
			  'success'
			)
		  }else{
			  Swal.fire(
				  'Unsuccessfull',
				  'Something went wrong. The password not changed',
				  'error'
				)
		  }
		})
	}
</script>