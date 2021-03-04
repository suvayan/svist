<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<div class="content" style="background-color: #64e2b1;">
	<div class="container">
		<div class="row">
			<div class="col-md-4" style="justify-content: center; align-self:center">
			  <div class="card ">
				<div class="card-header card-header-icon">
				  <div class="card-icon" style="background-color:#ebe9e8 !important">
					<img src="<?php echo base_url(); ?>assets/img/logo0.png" height="40" >
				  </div>
				  <h4 class="card-title">Welcome Back</h4>
				</div>
				<div class="card-body ">
				  <?php
					if($this->session->flashdata('error')!=NULL){
						echo '<div class="alert alert-warning">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <i class="material-icons">close</i>
								</button>
								<span>
								  <b> Warning - </b> '.$this->session->flashdata('error').'</span>
							  </div>';
					}
					if($this->session->flashdata('success')!=NULL){
						echo '<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								  <i class="material-icons">close</i>
								</button>
								<span>
								  <b> Success - </b> '.$this->session->flashdata('success').'</span>
							  </div>';
					}
				  ?>
				  <form class="form" id="frmLogin">
					  <div class="form-group">
						<label for="email">Email Address</label>
						<input type="text" name="username" id="username" class="form-control"/>
					  </div>
					  <div class="form-group">
						<label for="passwd">Password</label>
						<input type="password" name="password" id="password" class="form-control" autocomplete="off"/>
					  </div>
					  <div class="form-group">
						<div class="form-group g-recaptcha" data-sitekey="6LcAodEZAAAAAD_eDVKc-mC1XluCkg4P1kMKzIWy"></div>
					  </div>
					  <div class="form-group">
						<input type="reset" style="display:none"/>
						<button type="submit" class="home-btn pull-left" id="btn_login">Login</button>
						<input type="reset" style="visibility:hidden;"/>
						<a href="javascript:;" class="pull-right" onClick="SendOTP();" style="font-size:14px;">Forgot Password ?</a>
					  </div>
				  </form>
				  
				</div>
				<div class="card-footer">
					<a href="<?php echo base_url('register/student'); ?>" style="font-size:14px">New here? <b>Create New Account</b></a>
				</div>
			  </div>
			</div>
			<div class="col-md-8 img-container" style="justify-content: center; align-self:center">
				
				<iframe width="100%" height="315" src="https://www.youtube.com/embed/kT6g-j5gCDg" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
			</div>
		</div>
	</div>
</div>