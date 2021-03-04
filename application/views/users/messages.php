<style>
.dropdown bootstrap-select {
	width:100% !important;
}
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
.ui-dialog-titlebar-close .material-icons {
	font-size: 16px !important;
}
</style>
<div class="loader" id="loading" style="display:none;">
	<img src="<?php echo base_url().'assets/img/loading.gif'; ?>">
</div>
<div class="content">
	<div class="container-fluid">
		<div class="row">
		
			<aside class="col-md-4">
				<div class="card">
					<div class="card-body">
						<div id="accordion" role="tablist">
							<div class="card-collapse">
							  <div class="card-header" role="tab" id="headingOne">
								<h5 class="mb-0">
								  <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									Teachers
									<i class="material-icons">keyboard_arrow_down</i>
								  </a>
								</h5>
							  </div>
							  <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
								<div class="card-body" id="teacher_list">
									<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">Loading...</div>
								</div>
							  </div>
							</div>
							<div class="card-collapse">
							  <div class="card-header" role="tab" id="headingTwo">
								<h5 class="mb-0">
								  <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Students
									<i class="material-icons">keyboard_arrow_down</i>
								  </a>
								</h5>
							  </div>
							  <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body" id="student_list">
									<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">Loading...</div>
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</aside>
			<div class="col-md-8" id="">
				<div class="card">
					<div class="card-body" id="msg_details">
						<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">Loading...</div>
					</div>
					<div id="user_model_details">
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

<script src="<?php echo base_url('assets/js/messages.js'); ?>"></script>