<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<div class="content" style="background-color: #0084c7;">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
			  <div class="card">
				<div class="card-header card-header-icon">
				  <div class="card-icon" style="background-color:#ebe9e8 !important">
					<img src="<?php echo base_url(); ?>assets/img/logo0.png" height="40" >
				  </div>
				  <h4 class="card-title">Old Student Registeration</h4>
				  <h5 class="text-danger small">Note: For wrong input will lead to cancelation; * are mandatory</h5>
				</div>
				<div class="card-body ">
					<?php
						if($this->session->flashdata('error')!=''){
							echo '<div class="alert alert-warning alert-dismissible">
							  <button type="button" class="close" data-dismiss="alert">&times;</button>
							  '.$this->session->flashdata('error').'
							</div>';
						}
					?>
					<form class="form" method="POST" action="<?php echo base_url('Login/oldstudregister'); ?>" id="frmStudRegister">
						<div class="row">
							<div class="col-sm-6 mx-auto">
								<div class="form-group">
									<select class="selectpicker" data-title="Admission year <span class='text-danger'>*</span>" data-style="select-with-transition" data-size="5" name="ayear" id="ayear">
										<?php 
											foreach($acayear as $arow){
												echo '<option value="'.$arow->sl.'">'.$arow->yearnm.'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<select class="selectpicker" data-title="Institute <span class='text-danger'>*</span>" data-style="select-with-transition" name="org" id="org">
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
							<div class="col-sm-6">
								<div class="form-group">
									<select class="selectpicker" data-title="Departments <span class='text-danger'>*</span>" data-style="select-with-transition" data-size="5" name="dept" id="dept">
									</select>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<select class="selectpicker" data-title="Programs <span class='text-danger'>*</span>" data-style="select-with-transition" name="prog" id="prog">
									</select>
								  </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="lname"></label>
									<select class="selectpicker" data-title="Semester <span class='text-danger'>*</span>" data-style="select-with-transition" data-size="5" name="sem" id="sem">
									</select>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="enroll">Enrollment <span class="text-danger">*</span></label>
									<input type="text" name="enroll" id="enroll" class="form-control"/>
								  </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="rollno">Roll No.</label>
									<input type="text" name="rollno" id="rollno" class="form-control"/>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="fname">Firstname <span class="text-danger">*</span></label>
									<input type="text" name="fname" id="fname" class="form-control"/>
									<input type="hidden" name="user_type" id="user_type" value="student"/>
								  </div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="lname">Lastname <span class="text-danger">*</span></label>
									<input type="text" name="lname" id="lname" class="form-control"/>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="email">Email <span class="text-danger">*</span></label>
									<input type="text" name="email" id="email" class="form-control"/>
								  </div>	
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="phone">Phone <span class="text-danger">*</span></label>
									<input type="text" name="phone" id="phone" class="form-control"/>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="passwd">Password <span class="text-danger">*</span></label>
									<input type="password" name="passwd" id="passwd" class="form-control" autocomplete="off"/>
								  </div>
								  
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="repasswd">Retype Password <span class="text-danger">*</span></label>
									<input type="password" name="repasswd" id="repasswd" class="form-control" autocomplete="off"/>
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<div class="form-group g-recaptcha" data-sitekey="6LcAodEZAAAAAD_eDVKc-mC1XluCkg4P1kMKzIWy"></div>
								  </div>
								  <div class="form-group">
									<input type="reset" style="display:none"/>
									<button type="submit" class="home-btn pull-left" id="btn_login">Register</button>
									<input type="reset" style="visibility:hidden;"/>
								  </div>
							</div>
						</div>
					</form>
				</div>
				<div class="card-footer">
					<a href="<?php echo base_url(); ?>" style="font-size:14px">Already have an account? <b>Sign In</b></a>
				</div>
			  </div>
			</div>
			<div class="col-lg-6" style="align-self:center; justify-content:center;">
				<img src="<?php echo base_url().'assets/img/studentreg.gif'; ?>" class="img-responsive w-100"/>
			</div>
		</div>
	</div>
</div>
<script>
$('#org').on('change', ()=>{
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
	var dept = $('#dept').val();
	var acayear = $('#ayear').val();
	$.ajax({
		url: baseURL+'Login/getPrograms',
		type: 'GET',
		data: { dept: dept, acayear: acayear },
		success: (resp)=>{
			$('#prog').html(resp);
			$('#prog').selectpicker('refresh');
		}
	});
});
$('#prog').on('change', ()=>{
	var prog = $('#prog').val();
	$.ajax({
		url: baseURL+'Login/getSemesters',
		type: 'GET',
		data: { prog: prog },
		success: (resp)=>{
			$('#sem').html(resp);
			$('#sem').selectpicker('refresh');
		}
	});
});
</script>