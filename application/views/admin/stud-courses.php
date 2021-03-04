<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<script src="<?php echo base_url(); ?>assets/js/plugins/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>  
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
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
    <div class="container-fluid">
        <div class="row">
			
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-info card-header-text">
						<div class="card-text">
							<h4 class="card-title">Program List</h4>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<form method="GET" id="frmAdmList" action="#">
							
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<select class="selectpicker" data-title="Admission Year *" data-style="select-with-transition" name="acayear" id="acayear" required="true">
													<?php 
														foreach($acayear as $arow){
															echo '<option value="'.$arow->sl.'">'.$arow->yearnm.'</option>';
														}
													?>
												</select>
											</div>	
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<select class="selectpicker w-100" data-title="Organisation *" data-style="select-with-transition" name="org" id="org" required="true">
												<?php
													if(!empty($orgs)){
														foreach($orgs as $orow){
															echo '<option value="'.$orow->id.'">'.$orow->title.'</option>';
														}
													}
												?>
												</select>
											</div>	
										</div>								
									</div>
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<select class="selectpicker w-100" data-title="Department *" data-style="select-with-transition" name="dept" id="dept" required="true">
												
												</select>
											</div>	
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<select class="selectpicker w-100" data-title="Program *" data-style="select-with-transition" name="prog" id="prog" required="true">
												
												</select>
											</div>	
										</div>
										<div class="col-sm-12">
											<div class="form-group text-center">
												<button type="button" class="btn btn-info btn-sm" id="searchbtn">Search</button>
											</div>	
										</div>
									</div>
								</form>
								
							</div>
							<div class="col-md-6">
								
							</div>
						</div>

					</div>
				</div>
			</div>
			
			<div class="col-md-12">
				<div class="progress-bar progress-bar-striped progress-bar-animated" id="prg_progress" style="width:100%; display:none;">Loading the list. Please wait...</div>
				<div id="adm_list"></div>
			</div>
			
		</div>
	</div>
</div>
<script>
	function getStudCoursesList(pid)
	{
		$('#prg_progress').show();
		$('#adm_list').html("");
		$.ajax({
			url: baseURL+'Admin/getStudCoursesList',
			type: 'GET',
			data: { pid: pid },
			success: (resp, xhr)=>{
				$('#prg_progress').hide();
				$('#adm_list').html(resp);
			}
		});
	}
	$('#acayear').on('change', ()=>{
		$('#org').selectpicker('refresh');
		$('#dept').selectpicker('refresh');
		$('#prog').selectpicker('refresh');
	})
	$('#org').on('change', ()=>{
		$('#dept').selectpicker('refresh');
		$('#prog').selectpicker('refresh');
		var org = $('#org').val();
		$.ajax({
			url: baseURL+'Login/getDepartments',
			type: 'GET',
			data: { org: org },
			success: (resp)=>{
				$('#dept').html(resp);
				$('#dept').selectpicker('refresh');
			}
		});
	});
	$('#dept').on('change', ()=>{
		$('#prog').selectpicker('refresh');
		var dept = $('#dept').val();
		var acayear = $('#acayear').val();
		var ptype = '';
		$.ajax({
			url: baseURL+'Admin/getPrograms',
			type: 'GET',
			data: { dept: dept, acayear: acayear, ptype: ptype },
			success: (resp)=>{
				$('#prog').html(resp);
				$('#prog').selectpicker('refresh');
			}
		});
	});
	$('#searchbtn').on('click', ()=>{
		var pid = $('#prog').val();
		if(pid != ''){
			getStudCoursesList(pid);
		}else{
			$.notify({
				icon:"add_alert",
				message: "Please select a program."},
				{type:"danger",
				timer:3e3,
				placement:{from:'top',align:'right'}
			})
		}
		
	})
</script>