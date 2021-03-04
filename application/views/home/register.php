<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<div class="content" style="background-color: <?php echo ($userType=='student')? '#0084c7':'white'; ?>">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
			  <div class="card">
				<div class="card-header card-header-icon">
				  <div class="card-icon" style="background-color:#ebe9e8 !important">
					<img src="<?php echo base_url(); ?>assets/img/logo0.png" height="40" >
				  </div>
				  <h4 class="card-title">Sign Up Now</h4>
				</div>
				<div class="card-body ">
					<form class="form" method="" action="#" id="register">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="fname">Institute <span class="text-danger">*</span></label>
									<select class="selectpicker" data-title="Select an institution" data-style="select-with-transition" name="org" id="org">
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
									<label for="lname">Departments <span class="text-danger">*</span></label>
									<select class="selectpicker" multiple data-title="Select a department" data-style="select-with-transition" data-size="5" name="dept[]" id="dept">
									<?php
										if(!empty($strms)){
											foreach($strms as $stow){
												echo '<option value="'.$stow->id.'">'.$stow->title.'</option>';
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
									<label for="fname">Firstname <span class="text-danger">*</span></label>
									<input type="text" name="fname" id="fname" class="form-control"/>
									<input type="hidden" name="user_type" id="user_type" value="<?php echo $userType; ?>"/>
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
						<?php /*if($userType=='teacher'){ ?>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="designation">Designation <span class="text-danger">*</span></label>
									<input type="text" name="designation" id="designation" class="form-control"/>
								  </div>
								  
							</div>
						</div>
						<?php }*/ ?>
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
				<img src="<?php echo base_url().'assets/img/'.$userType.'reg.gif'; ?>" class="img-responsive w-100"/>
			</div>
		</div>
	</div>
</div>