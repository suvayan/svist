<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<div class="content">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
			  <div class="card ">
				<div class="card-header card-header-info">
					<h3 class="card-title text-center">Request Demo</h3>
				</div>
				<div class="card-body ">
					<form class="form" method="POST" id="reqDemo">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="fname">Firstname <span class="text-danger">*</span></label>
									<input type="text" name="fname" id="fname" class="form-control"/>
									
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
									<label for="job_title">Job Title</label>
									<input type="text" name="job_title" id="job_title" class="form-control"/>
								  </div>
								  
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="company">Company</label>
									<input type="text" name="company" id="company" class="form-control">
								  </div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="desc">Description</label>
									<textarea name="desc" id="desc" class="form-control"></textarea>
								  </div>
								<div class="form-group">
									<button type="submit" class="btn btn-info btn-sm" id="btn_login">Send Request</button>
									<input type="reset" style="visibility:hidden;"/>
								  </div>
							</div>
						</div>						  
				  </form>
				  
				</div>
				
			  </div>
			</div>
		</div>
	</div>
</div>