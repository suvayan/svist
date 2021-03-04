<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="content">
	<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
			  <div class="card ">
				<div class="card-header card-header-rose card-header-icon">
				  <div class="card-icon" style="background-color:#ebe9e8 !important">
					<img src="<?php echo base_url(); ?>assets/img/logo0.png" height="40" >
				  </div>
				  <h4 class="card-title">Reset Your Password</h4>
				</div>
				<div class="card-body ">
				  <?php if($this->session->flashdata('success')){ ?>
					  <div class="alert alert-success">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <i class="material-icons">close</i>
						</button>
						<span>
						  <?php echo $this->session->flashdata('success'); ?></span>
					  </div>
					<?php } if($this->session->flashdata('error')){ ?>
						<div class="alert alert-warning">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							  <i class="material-icons">close</i>
							</button>
							<span>
							  <?php echo $this->session->flashdata('error'); ?></span>
						  </div>
					<?php } ?>
				  <form class="form-horizontal" id="frmReset">
					<div class="form-group">
					  <label for="exampleEmail" class="bmd-label-floating">OTP</label>
					  <input type="text" class="form-control" name="vcode" id="vcode">
					  <input type="hidden" name="email" id="email" value="<?php echo $email; ?>">
					</div>
					<div class="form-group">
					  <label for="examplePass" class="bmd-label-floating">New Password</label>
					  <input type="password" class="form-control" name="password" id="password">
					</div>
					<div class="form-group">
					  <label for="examplePass" class="bmd-label-floating">Confirm Password</label>
					  <input type="password" class="form-control" name="cpassword" id="cpassword">
					</div>
					<div class="form-group">
					  <div class="form-group g-recaptcha" data-sitekey="6LcAodEZAAAAAD_eDVKc-mC1XluCkg4P1kMKzIWy"></div>
					</div>
					<div class="form-group">
					  <input type="reset" style="display:none"/>
					  <button type="submit" class="home-btn pull-left" id="btn_login">Update</button>
					</div>
				  </form>
				</div>
			  </div>
			</div>
		</div>
	</div>
</div>